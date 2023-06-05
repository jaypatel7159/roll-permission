<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|confirmed'
        // ]);

        $user = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('AuthApp')->accessToken;

        return response()->json(['token' => $token], 200);
    }
    public function login(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [

            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Unauthorised'], 401);
        }

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('AuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
