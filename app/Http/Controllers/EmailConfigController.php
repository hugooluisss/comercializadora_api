<?php

namespace App\Http\Controllers;

use App\Models\EmailConfig;
use Illuminate\Http\Request;

class EmailConfigController extends Controller
{
    public function index(Request $request){
        $row = EmailConfig::first();
        return $row;
    }

    public function store(Request $request){
        $data = $request->all();
        $row = EmailConfig::first();
        if (is_null($row)){
            EmailConfig::create($data);
        }else{
            $row->update($data);
        }

        return response()->json(['message' => 'Email Config Updated Successfully']);
    }
}
