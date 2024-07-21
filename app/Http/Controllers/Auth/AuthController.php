<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes;

class AuthController extends Controller
{
    #[Attributes\Post(
        path: '/api/register',
        requestBody: new Attributes\RequestBody(
            content: new Attributes\JsonContent(
                properties: [
                    new Attributes\Property(
                        property: 'name',
                        type: 'string',
                        maxLength: 255
                    ),
                    new Attributes\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email',
                        maxLength: 255
                    ),
                    new Attributes\Property(
                        property: 'password',
                        type: 'string',
                        maxLength: 255,
                        minLength: 8,
                    ),
                ]
            )
        ),
        tags: ['Auth'],
        responses: [
            new Attributes\Response(
                response: 200,
                description: 'Ok',
                content: new Attributes\JsonContent(
                    properties: [
                        new Attributes\Property(
                            property: 'status',
                            type: 'string',
                            example: 'ok'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function register(UserRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return response()->json(['status' => 'ok'], 201);
    }

    #[Attributes\Post(
        path: '/api/login',
        requestBody: new Attributes\RequestBody(
            content: new Attributes\JsonContent(
                properties: [
                    new Attributes\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email',
                        maxLength: 255
                    ),
                    new Attributes\Property(
                        property: 'password',
                        type: 'string',
                        maxLength: 255,
                        minLength: 8,
                    ),
                ]
            )
        ),
        tags: ['Auth'],
        responses: [
            new Attributes\Response(
                response: 200,
                description: 'Ok',
                content: new Attributes\JsonContent(
                    properties: [
                        new Attributes\Property(
                            property: 'token',
                            type: 'string',
                        ),
                    ]
                )
            ),
        ]
    )]
    public function login(UserLoginRequest $request)
    {
        /** @var User $user */
        $user = User::query()->where('email', $request->get('email'))->first();

        if (is_null($user) || !Hash::check($request->get('password'), $user->password)) {
            return response()->json(['error' => 'Неверные учетные данные'], 401);
        }

        $token = $user->createToken('token-name')->accessToken;

        return response()->json(['token' => $token]);
    }
}
