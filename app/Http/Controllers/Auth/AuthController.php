<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {

        // Создание пользователя
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

    }

    public function login(UserLoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('token-name')->plainTextToken;

            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Неверные учетные данные'], 401);
    }
}
