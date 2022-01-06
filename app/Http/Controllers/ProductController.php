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

    public function get(int $id){
        $product = Product::with('shops')->find($id);

        return $product;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setStock(Request $request){
        $data = $request->all();
        $sentence = DB::table('product_shop')->where([
            ['shop_id', '=', $data['shop_id']],
            ['product_id', '=', $data['product_id']],
        ]);
        
        if (!$sentence->exists()){
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['created_at'] = date('Y-m-d H:i:s');
            DB::table('product_shop')->insert($data);

            return response(null, 200);
        }else{
            // $data['updated_at'] = date('Y-m-d H:i:s');
            // DB::table('product_shop')->update($data);

            return response(null, 500);
        }
    }

    public function deleteStock($id, $shop_id){
        DB::table('product_shop')->where([
            ['shop_id', '=', $shop_id],
            ['product_id', '=', $id],
        ])->delete();

        return response(null, 200);
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
