<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name'=>'required|string',
            'last_name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed',
            'phone'=>'required|string|min:9|max:13',
            'country'=>'required|string',
            'state'=>'required|string',
            'city'=>'required|string',
            'zip_code'=>'required|string',
        ]);

        $user = User::create([
            'last_name' => $fields['last_name'],
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'phone' => $fields['phone'],
            'country' => $fields['country'],
            'state' => $fields['state'],
            'city' => $fields['city'],
            'zip_code' => $fields['zip_code'],
        ]);


        //I need to search for the created user because if not, user->role return null
        $created_user = User::findOrFail($user->id);
        $created_user->role;

        $token = $created_user->createToken('myapptoken')->plainTextToken;

        $response = [
            'ok'=> true,
            'user'=>$created_user,
            'token'=>$token
        ];


        return response($response, 201);
    }


    public function login(Request $request)
    {
        $fields = $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        //Chech email
        $user = User::where('email', $fields['email'])->first();
        //Check password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response(['ok'=>false,'message'=>'Wrong email or password']);
        }
        
        $user->role;
            
        $token = $user->createToken('myapptoken')->plainTextToken;
            

        $response = [
            'ok'=> true,
            'user'=>$user,
            'token'=>$token
        ];

        return response($response, 201);
    }


    public function logout(Request $request)
    {
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        $response = [
            'ok'=> true,
            'message'=>'Logged out'
        ];
        
        return $response;
    }

    public function userRoles(){

        $value = session();

        $user = User::findOrFail(1);
        $role = $user->getRoleNames();
        return [$role, $value];
    }

    public function checkAuth()
    {
        $user = request()->user();

        $token = request()->bearerToken();

        return ['ok'=> true, 'user'=> $user, 'token'=>$token];
    }
}
