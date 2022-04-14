<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Templates\TItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller{
    public function index(){
        $customer = Customer::where('user_id', Auth::id())->first();
        if (is_null($customer)){
            return Order::with(['items', 'customer', 'status'])
                ->orderBy('updated_at', 'desc')
                ->get();
        }else{
            return Order::with(['items', 'customer', 'status'])
                ->where('customer_id', $customer->id)
                ->orderBy('updated_at', 'desc')
                ->get();
        }
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
            $order->save();

            $items = $this->createListItems($data['items']);
            $order->items()->sync($items);

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ], 500);    
        }
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
                'price' => $object->getPrice()
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

        return $this->get($id);
    }
}
