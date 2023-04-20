<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $user = User::latest()->get();
        return $user;
    }

    public function store(Request $request){
        // $request->validate([
        //     'email' => 'required|unique:users,email'
        // ]);
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> bcrypt($request->password)
        ]);

        return $user;
    }

    public function update(Request $request,User $user){
        // $request->validate([
        //     'email' => 'required|unique:users,email,'.$user->id,
        // ]);
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6',
        ]);
        $user->update([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> $request->password ? bcrypt($request->password) : $user->password
        ]);
        return $user;
    }
    public function destory(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
