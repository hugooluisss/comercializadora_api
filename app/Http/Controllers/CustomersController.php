<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Customer::with('user')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();

            $data = $request->get('user');
            $data['password'] = bcrypt($data['password']);
            $data['role'] = 'CLIENTE';
            $data['name'] = $request->get('firstname')." ".$request->get("lastname");
            $user = User::create($data);

            $data = $request->all();
            unset($data['user']);
            $data['user_id'] = $user->id;
            $data['confirmed'] = $data['confirmed']??0;
            $customer = Customer::create($data);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ], 500);    
        }

        DB::commit();
        return response()->json(Customer::find($customer->id)->with('user')->first(), 200);
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
        $customer = Customer::findOrFail($id);
        $user = User::findOrFail($customer->user_id);
        $data = $request->all();

        if ($data['user']['password'] == '')
            $data['user']['password'] = $user->password;

        $user->update($data['user']);
        unset($data['user']);

        $customer->update($data);
        return response()->json(Customer::find($customer->id)->with('user')->first(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $customer = Customer::findOrFail($id);
        $user = User::findOrFail($customer->user_id);
        $user->delete();

        return response(null, 204);
    }
}