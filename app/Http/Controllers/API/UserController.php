<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $users = User::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%");
            })
            ->latest()
            ->paginate(5);
        return $users;

        // $user = User::latest()->paginate();
        // return $user;
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
        return $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);
        // return response()->json(['success' => true,'data' => $user]);
        // return $user;
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
            'password'=> $request->password ? Hash::make($request->password) : $user->password
        ]);
        return $user;
    }

    public function changeRole(User $user)
    {
        $user->update([
            'role' => request('role'),
        ]);

        return response()->json(['success' => true]);
    }

    public function bulkDelete()
    {
        User::whereIn('id', request('ids'))->delete();

        return response()->json(['message' => 'Users deleted successfully!']);
    }

    public function destory(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
