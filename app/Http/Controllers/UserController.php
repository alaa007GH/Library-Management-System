<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
    $registerUserData = $request->validate([
        'name'=>'required|string',
        'email'=>'required|string|email|unique:users',
        'password'=>'required|min:8'
    ]);
    $user = User::create([
        'name' => $registerUserData['name'],
        'email' => $registerUserData['email'],
        'password' => Hash::make($registerUserData['password']),
    ]);
            $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

    return response()->json([
        'message' => 'User Created ',
        'token' => $token 
    ]);
}


 public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function logout(){
    auth()->user()->tokens()->delete();

    return response()->json([
      "message"=>"logged out"
    ]);
}

public function get(){
    $data = User::get();
    
    return ['data'=>$data ];
}

public function create(Request $request){
$data = User::create([
   
    'first_name' => $request->first_name,
    'last_name' => $request->last_name,
    'phone_number' => $request->phone_number,
    'address' => $request->address,
    'email' => $request->email,
    'password' => $request->password,

]);
return $data;
}
public function update(Request $request,$id){
$data = User::where('id',$id)->update([

    'first_name' => $request->first_name,
    'last_name' => $request->last_name,
    'phone_number' => $request->phone_number,
    'address' => $request->address,
    'email' => $request->email,
    'password' => $request->password,

   
]);
return $data;
}

public function delete(Request $request,$id){
$data = User::where('id',$id)->delete();
return $data;
}
}
