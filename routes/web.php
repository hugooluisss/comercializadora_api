<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
});

Route::get('/artisan/cache', function(){
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    //Artisan::call('config:cache');
    					
    return redirect('/');
});

Route::get('/artisan/migrate', function(){
    //Artisan::call('migrate', ['--force' => true]);
    Artisan::call('migrate', [
        '--path' => 'vendor/laravel/passport/database/migrations',
        '--force' => true,
    ]);
    //Artisan::call("passport:client --client");
    exec("php ../artisan passport:install");
    return redirect('/');
});

Route::get('/artisan/migrate/new', function(){
    Artisan::call('migrate --env=production');
});