<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function hi(){
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
}
