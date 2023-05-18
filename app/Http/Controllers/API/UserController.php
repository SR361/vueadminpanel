<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\CustomFileUpload;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    use CustomFileUpload;
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

    public function updateProfile(Request $request){
        // dd($request->file());
        request()->validate([
            'email' => 'required|unique:users,email,' . Auth::guard('api')->id(),
        ]);

        $user = User::find(Auth::guard('api')->id());
        $updatedata= array(
            'name'          => $request->name,
            'email'         => $request->email,
            'designation'   => $request->designation,
            'education'     => $request->education,
            'location'      => $request->location,
            'skills'        => $request->skills,
        );
        if($request->file('profile_photo')){
            $profile_photo = request()->file('profile_photo');

            $this->deleteFile(
                $user->getRawOriginal('profile_photo'),
                'uploads/profile_photo/'
            );

            $imagename = $this->uploadFile(
                $profile_photo,
                'uploads/profile_photo'
            );
            $updatedata['profile_photo'] = $imagename;
        }
        $user->update($updatedata);
        return $user;
    }

    public function getUserProfile(){
        return User::find(Auth::guard('api')->id());
    }

    public function changePassword(Request $request){

        $validated = request()->validate([
            'password'               => 'required|confirmed|min:6',
            'password_confirmation'     => 'required|min:6'
        ]);

        $user = Auth::guard('api')->user();
        $user->password = Hash::make($request->password);
        $user->save();
        $output = array('status' => 200, 'message'=>'Password Updated successfully.');
        return response()->json($output, 200);
    }
}
