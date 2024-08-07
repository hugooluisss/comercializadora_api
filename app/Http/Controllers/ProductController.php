<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

use function PHPUnit\Framework\fileExists;

class ProductController extends Controller
{
    public function index(){
        return Product::withTrashed()->with(['category', 'customers'])->get();
    }

    public function search(Request $request){
        $queryParam = $request->query('query');
        $query = Product::withTrashed()->with(['category', 'customers']);

        $query->orWhere('name', 'like', "%$queryParam%");
        $query->orWhere('sku', 'like', "%$queryParam%");
        $query->orWhere('description', 'like', "%$queryParam%");
        
        return $query->get();
    }

    private function getWithStock(){
        return Product::with(['category', 'customers'])->where('stock', '>', 0);
    }

    public function haveStock(){
        $products = $this->getWithStock()->get();
        return $products;
    }

    public function searchByCategory(int $category_id){
        $query = $this->getWithStock();
        
        $query->where(['category_id' => $category_id]);
        
        return $query->get();
    }

    public function get(int $id){
        $product = Product::with(['category', 'customers'])->find($id);

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
        $products[] = ['Categoria', 'Subcategoría', 'SKU', 'Nombre', 'Descripción', 'urlImagen', 'Stock', 'Precio1', 'Con descuento', 'Precio2', 'Con descuento', 'Precio3', 'Con descuento', 'Límite 1', 'Límite 2', 'Marca', 'Borrar'];
        
        foreach(Product::with(['category'])->withTrashed()->get() as $product){
            $row = [
                $categoriesParents[$product->category->category_parent_id]->name,
                $product->category->name,
                $product->sku,
                $product->name,
                $product->description,
                $product->image,
                $product->stock,
                $product->price1,
                (is_null($product->with_discount1) or $product->price1 < $product->with_discount1)?$product->price1:$product->with_discount1,
                $product->price2,
                (is_null($product->with_discount2) or $product->price2 < $product->with_discount2)?$product->price2:$product->with_discount2,
                $product->price3,
                (is_null($product->with_discount3) or $product->price3 < $product->with_discount3)?$product->price3:$product->with_discount3,
                $product->limit1,
                $product->limit2,
                $product->brand,
                is_null($product->delete_at)?0:1
            ];

            $products[] = $row;
        }

        return $products;
    }

    public function import(Request $request){
        $rows = explode("\r\n", $request->post('csv', ''));
        $success = $this->importData(lines: $rows);

        return response(json_encode([
            'success' => $success
        ]), 200);
    }

    public function importFile(){
        $fileName = 'productsImport.csv';

        $file = getcwd()."/{$fileName}";

        if (!file_exists($file)){
            return response(json_encode([
                'message' => 'The file no exist'
            ]), 500);
        }

        $content = file_get_contents($file);

        $rows = explode("\r\n", $content??'');
        $success = $this->importData(lines: $rows);

        return response(json_encode([
            'success' => $success
        ]), 200);
    }

    private function importData(array $lines): int{
        $rows = $lines;

        array_shift($rows);
        $categories = [];
        $success = 0;
        foreach($rows as $row){
            try{
                $productForImport = $this->createObjectCsv(data: str_getcsv($row));

                $product = Product::withTrashed()->where(['sku' => $productForImport->sku])->first();
                if (is_null($product)){
                    $product = new Product();
                }

                $product->sku = $productForImport->sku;
                $product->name = $productForImport->name;
                $product->description = $productForImport->description;
                $product->image = $productForImport->image;
                $product->stock = $productForImport->stock;
                $product->price1 = $productForImport->price1;
                $product->price2 = $productForImport->price2;
                $product->price3 = $productForImport->price3;
                $product->with_discount1 = $this->validateDiscount($productForImport->price1, $productForImport->with_discount1);
                $product->with_discount2 = $this->validateDiscount($productForImport->price2, $productForImport->with_discount2);
                $product->with_discount3 = $this->validateDiscount($productForImport->price3, $productForImport->with_discount3);
                $product->limit1 = $productForImport->limit1;
                $product->limit2 = $productForImport->limit2;
                $product->brand = $productForImport->brand;

                $subcategory = $this->searchCategories(
                    nameSubcategory: $productForImport->subcategory, 
                    nameCategory: $productForImport->category, 
                    bufferCategories: $categories);

                $product->category_id = $subcategory->id;

                $success += $product->save()?1:0;

                if ($productForImport->forDelete){
                    $product->delete();
                }else{
                    $product->restore();
                }
            }catch(\Exception $e){
                error_log($e->getMessage());
            }
        }
        return $success;
    }

    private function validateDiscount(float $price, float $withDiscount = null): null | float{

        return match(true){
            is_null($withDiscount) => null,
            $withDiscount == $price => null,
            $withDiscount > $price => null,
            default => $withDiscount
        };
    }

    private function createObjectCsv(array $data): stdClass{
        $productForImport = new stdClass;
        $productForImport->sku = $data[2];
        $productForImport->name = $data[3];
        $productForImport->description = $data[4];
        $productForImport->image = $data[5];
        $productForImport->stock = (int) $data[6];
        $productForImport->price1 = (float) $data[7];
        $productForImport->with_discount1 = (float) $data[8];
        $productForImport->price2 = (float) $data[9];
        $productForImport->with_discount2 = (float) $data[10];
        $productForImport->price3 = (float) $data[11];
        $productForImport->with_discount3 = (float) $data[12];
        $productForImport->limit1 = (float) $data[13];
        $productForImport->limit2 = (float) $data[14];
        $productForImport->brand = (string) $data[15];
        $productForImport->category = $data[0];
        $productForImport->subcategory = $data[1];
        $productForImport->forDelete = (bool) $data[16] == '1';

        return $productForImport;
    }

    private function searchCategories(string $nameSubcategory, string $nameCategory, array &$bufferCategories): Category{
        $key = "{$nameSubcategory}_{$nameCategory}";

        if (isset($bufferCategories[$key])) return $bufferCategories[$key];

        $subcategories = Category::with(['category_parent'])->where(['name' => $nameSubcategory])->get();
        foreach($subcategories as $subcategory){
            if ($subcategory->category_parent->name == $nameCategory){
                $bufferCategories[$key] = $subcategory;

                return $subcategory;
            }
        }

        throw new Exception("{$nameSubcategory} - {$nameCategory} Category not found");
    }
}
