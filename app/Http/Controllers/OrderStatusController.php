<?php

namespace App\Http\Controllers;

use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller{
    public function index(){
        return OrderStatus::all();
    }
}
