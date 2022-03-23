<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function export(){
        $categoriesParents = Category::getParents();
        $products = [];
        $products[] = ['id', 'Categoria', 'Subcategoría', 'SKU', 'Nombre', 'Descripción', 'urlImagen', 'Stock', 'Precio1', 'Precio2', 'Precio3', 'Borrar'];
        
        foreach(Product::with(['category'])->withTrashed()->get() as $product){
            $row = [
                $product->id,
                $categoriesParents[$product->category->category_parent_id]->name,
                $product->category->name,
                $product->sku,
                $product->name,
                $product->description,
                $product->image,
                $product->stock,
                $product->price1,
                $product->price2,
                $product->price3,
                is_null($product->delete_at)?0:1
            ];

            $products[] = $row;
        }

        return $products;
    }

    private function findCategoryParent(Category $category){
        
    }
}
