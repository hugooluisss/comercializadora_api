<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\EmailConfig;
use App\Models\Order;
use App\Models\Templates\TItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller{
    public function index(Request $request){
        $customer = Customer::where('user_id', Auth::id())->first();

        $end = date('Y-m-d', strtotime($request->get('end')));
        $start = date('Y-m-d', strtotime($request->get('start')));
        $query = Order::with(['items', 'customer', 'status'])
            ->whereBetween('updated_at', [$start, $end])
            ->orderBy('id', 'desc');

        if (!is_null($customer)){
            $query = $query->where('customer_id', $customer->id);
        }

        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        try{
            $customer = Customer::findOrFail($data['customer']['id']);
            DB::beginTransaction();
            $order = new Order();
            $order->customer_id = $customer->id;
            $order->shipping_detail = json_encode($data['shipping']);
            $order->shipping_price = $data['shipping']['price'];
            $order->shipping_name = $data['shipping']['description'];

            $order->payment_detail = json_encode($data['payment']);
            $order->payment_name = $data['payment']['description'];

            $order->save();

            $items = $this->createListItems($data['items']);
            $order->items()->sync($items);

            $emailConfig = EmailConfig::first();
            if(!is_null($emailConfig)){
                Log::info('Setting email config');

                $this->setEmailConfiguration();
                $emailAdmins = explode(",", $emailConfig->administrators);
            }else{
                Log::info('Using default email config');
                $emailAdmins = explode(",", env('MAIL_ADMINS', ''));
            }

            $this->sendMailNotification(order: $order);
            $to = $emailAdmins;

            Mail::send('emails.orderNotifyAdmin', ["order" => $order], function($message) use ($to) {
                $message
                    ->to($to)
                    ->subject('Nueva cotizacón');
            });

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ], 500);    
        }
    }

    private function setEmailConfiguration(): void{
        $emailConfig = \App\Models\EmailConfig::first();

        Config::set('mail.mailers.smtp.host', $emailConfig->host);
        Config::set('mail.mailers.smtp.port', $emailConfig->port);
        Config::set('mail.mailers.smtp.username', $emailConfig->username);
        Config::set('mail.mailers.smtp.password', $emailConfig->password);
        Config::set('mail.mailers.smtp.encryption', $emailConfig->encryption);
        Config::set('mail.mailers.smtp.address', $emailConfig->address);
        Config::set('mail.mailers.smtp.name', $emailConfig->fromName);
    }

    private function createListItems(array $items): array{
        $data = [];
        foreach($items as $item){
            $item = (object) $item;

            $object = TItem::createWithData(
                product_id: $item->product['id'],
                amount: $item->amount,
            );

            $data[$object->product->id] = [
                'amount' => $object->amount,
                'price' => $object->getPriceSell(),
                'price_list' => $object->getPrice()
            ];
        }

        return $data;
    }

    public function get(int $id){
        return Order::with('customer', 'items', 'status')->findOrFail($id);
    }

    public function setStatus(Request $request, int $id){
        $order = Order::findOrFail($id);

        $order->status_id = $request->get('status');
        $order->save();

        $this->sendMailNotification(order: $order);

        return $this->get($id);
    }

    private function getTemplateAndSubjectForEmail(int $status_id): array{
        return match($status_id){
            1 => ['emails.orderCreated', "Cotización registrada"],
            2 => ['emails.orderInProcess', "Atendiendo tu pedido"],
            3 => ['emails.orderInRoute', "Estamos en ruta de entrega"],
            4 => ['emails.orderDelivered', "Pedido entregado"],
            5 => ['emails.orderWithProblems', "Tuvimos un problema con tu pedido"],
            6 => ['emails.orderCancel', "Pedido Cancelado"]
        };
    }

    private function sendMailNotification(Order $order): void{
        $user = User::findOrFail($order->customer->user_id);
        $to = $user->email;

        list($template, $subject) = $this->getTemplateAndSubjectForEmail($order->status_id??1);

        Mail::send($template, ["order" => $order], function($message) use ($to, $subject) {
            $message
                ->to($to)
                ->subject($subject);
        });
    }
}
