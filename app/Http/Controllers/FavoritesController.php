<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller{
    public function index(){
        return Product::with(['category', 'customers'])
        ->whereHas('customers', function($query){
            $customer = Customer::where('user_id', Auth::id())->first();
            return $query->where('customer_id', $customer->id);
        })
        ->get();
    }

    private function getCustomer(): Customer{
        $user = User::findOrFail(Auth::id());

        if (!$user->isCustomer()){
            throw new Exception("No eres un cliente");
        }

        $customer = Customer::where('user_id', $user->id)->first();

        return $customer;
    }

    public function addFavorites(Request $request){
        try{
            $customer = $this->getCustomer();

            $product = Product::findOrFail($request->post('product_id'));
            $product->customers()->attach($customer);

            return response(json_encode([]), 200);

        }catch(Exception $e){
            return response(json_encode([
                'error' => $e->getMessage()
            ]), 500);
        }
    }

    public function removeFavorites(int $product_id){
        try{
            $customer = $this->getCustomer();

            $product = Product::findOrFail($product_id);
            $product->customers()->detach($customer);

            return response(json_encode([]), 200);
        }catch(Exception $e){
            return response(json_encode([
                'error' => $e->getMessage()
            ]), 500);
        }
    }
}
