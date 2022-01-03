<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(){
        return Product::with('category')->get();
    }

    public function getPrices(int $id, int $shop_id){
        $prices = DB::table("prices")->where('shop_id', $shop_id)->where('product_id', $id)->get();
        

        return $prices;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $product = Product::findOrFail($id);
        $product->delete();

        return response(null, 204);
    }
}
