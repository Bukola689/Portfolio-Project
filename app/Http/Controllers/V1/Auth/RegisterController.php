<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Events\Register;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required',
        'email' => 'required',
        'password' => 'required'
        ]);

        if(User::where('email', $request->email)->first()) {
            return response()->json([
                'message' => 'Email Already Exist'
            ]);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->password);
        $user->save();

        $token  = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token,
        ];

        event(new Registered($user));

        event(new Register($user));

        return response($response, 201);
    }
}
