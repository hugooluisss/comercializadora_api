<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('shops')->get();
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($data['password']))
            $data['password'] = $this->setPassword($data['password']);
        else
            unset($data['password']);
            
        $obj = User::create($data);
        return response()->json($obj, 200);
    }

    private function setPassword(string $password){
        if ($password)
            return bcrypt($password);

        return false;
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
        $obj = User::findOrFail($id);
        $data = $request->all();
        if (isset($data['password']))
            $data['password'] = $this->setPassword($data['password']);
        else
            unset($data['password']);

        $obj->update($data);

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
        $obj = User::findOrFail($id);
        $obj->delete();

        return response(null, 204);
    }

    public function setShops(Request $request, $id){
        $user = User::findOrFail($id);
        
        $shops_id = array_map(static function($shop){
            return $shop['id'];
        }, $request->get('shops'));

        $user->shops()->sync($shops_id);

        return response()->json(User::find($id)->with('shops')->first(), 200);
    }
}
