<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bootstrap\RegisterFacades;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            "first_name" => $validatedData['first_name'],
            "last_name" => $validatedData['last_name'],
            "email" => $validatedData['email'],
            "password" => Hash::make($validatedData['password'])
        ]);

        $token = auth()->login($user);

        return response()->json([
            "status" => "success",
            "user" => $user,
            "authorization" => [
                "token" => $token
            ]
        ]);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password']
        ];

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                "status" => 'error',
                "message" => "Unauthorised"
            ], 401);
        };

        return response()->json(
            [
                "status" => "success",
                "user" => auth()->user(),
                "authorization" => [
                    "token" => $token,
                ]
            ]
        );
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            "authorization" => [
                "token" => Auth::refresh(),
            ]
        ]);
    }

    public function getActiveUser()
    {
        $activeUser = Auth::user();
        return response()->json($activeUser);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
