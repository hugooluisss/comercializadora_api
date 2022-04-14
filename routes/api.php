<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('get', [AuthController::class, 'getUser']);
        Route::get('getRole', [AuthController::class, 'getRole']);
    });
});

Route::group([
    'middleware' => 'auth:api'
  ], function() {
    Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
    Route::post('users/{id}/setShops', [UserController::class, 'setShops']);
    Route::resource('shops', ShopController::class)->except(['create', 'edit', 'show']);
    Route::resource('customers', CustomersController::class)->except(['create', 'edit', 'show']);
    Route::resource('categories', CategoryController::class)->except(['index', 'create', 'edit', 'show']);

    Route::resource('products', ProductController::class)->only(['index', 'destroy']);
    Route::get('products/export', [ProductController::class, 'export']);
    Route::post('products/import', [ProductController::class, 'import']);
    
    Route::get('favorites', [FavoritesController::class, 'index']);
    Route::post('favorites', [FavoritesController::class, 'addFavorites']);
    Route::delete('favorites/{product_id}', [FavoritesController::class, 'removeFavorites']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'get']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::post('orders/set_status/{id}', [OrderController::class, 'setStatus']);
    // Route::get('products/{id}', [ProductController::class, 'get']);

    Route::get('order_status', [OrderStatusController::class, 'index']);
});

Route::group([], function(){
  Route::get("categories", [CategoryController::class, 'index']);
  Route::get("category/{id}", [CategoryController::class, 'get']);
  Route::get("products/{category_id}", [ProductController::class, 'searchByCategory']);
  Route::get("products", [ProductController::class, 'haveStock']);
  Route::get("product/{id}", [ProductController::class, 'get']);

  Route::post('customers/signup', [CustomersController::class, 'signup']);
  Route::get('customers/user/{id}', [CustomersController::class, 'getFromUser']);
});