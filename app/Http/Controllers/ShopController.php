<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Shop::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $obj = Shop::create($request->all());
        return response()->json($obj, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $obj = Shop::findOrFail($id);

        $obj->update($request->all());

        return response()->json($obj, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = Shop::findOrFail($id);
        $obj->delete();

        return response(null, 204);
    }

    public function exportInventory($id){
        $shop = Shop::findOrFail($id);
        $products = Product::with('category')->whereHas('shops', function($q) use ($shop){
            $q->where('shop_id', '=', $shop->id);
        })->get();

        $data = [];
        $data[] = array('SKU', 'Nombre', 'Descripción', 'Categoría', 'Existencias', 'Precio Menudeo', 'Precio Medio Mayoreo', 'Precio Mayoreo');

        foreach ($products as $product) {
            $row['sku']  = $product->sku;
            $row['name']    = $product->name;
            $row['description']    = $product->description;
            $row['category']  = $product->category->name;
            
            $row['stock']  = $product->shops[0]->stock->stock;
            $row['price1']  = $product->shops[0]?->stock->price1;
            $row['price2']  = $product->shops[0]?->stock->price2;
            $row['price3']  = $product->shops[0]?->stock->price3;

            $data[] = $row;
        }

        return response()->json($data, 200);
    }
}
