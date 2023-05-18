<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponse;
use App\Http\Resources\AdminResource;
use App\Models\User;

class AuthController extends Controller
{
    use ApiResponse;
    protected $authUser;
    public $successStatus = 200;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('api')->user()) {
                $this->authUser = Auth::guard('api')->user();
            }
            return $next($request);
        });
    }

    public function login(Request $request){
        $validated = request()->validate([
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (is_null($user) || !Hash::check($request->password, $user->password)) {
            $output = array('status' => 422, 'message' => 'These credentials do not match our records!!');
            return response()->json($output, 201);
        }else{
            if($user->role == 'USER'){
                $output = array('status' => 422, 'message'=>'Sorry, you have not access to admin panel!');
                return response()->json($output, 201);
            }else{
                if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
                    $user = Auth::user();
                    $success['token'] =  $user->createToken('MyApp')->accessToken;

                    $token = md5(uniqid());
                    User::where('id', Auth::id())->update([ 'token' => $token ]);

                    $data = array(
                        'user'          => $user,
                        'token'         => $success['token'],
                        'access_token'  => $token
                    );
                    $output = array('status' => 200, 'message'=>'Login successfully!','data' => $data);

                    return response()->json($output, $this->successStatus);
                }else{
                    $output = array('status' => 422, 'message' => 'These credentials do not match our records!');
                    return response()->json($output, 201);
                }
            }
        }
    }
}
