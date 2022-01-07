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
        $products = Product::with("shops")->get();
        
        $products = array_filter($products, function($product){
            if (!isset($product->shops)) return false;


        });

        $fileName = 'inventoryExported.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('SKU', 'Nombre', 'Descripción', 'Categoría', 'Existencias', 'Precio Menudeo', 'Precio Medio Mayoreo', 'Precio Mayoreo');

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $product) {
                $row['sku']  = $product->sku;
                $row['name']    = $product->name;
                $row['description']    = $product->description;
                $row['categorie']  = $product->category_id;
                
                $row['stock']  = $product->shops[0]->stock->stock;
                // $row['price1']  = $product->shops[0]?->stock->price1;
                // $row['price2']  = $product->shops[0]?->stock->price2;
                // $row['price3']  = $product->shops[0]?->stock->price3;

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
