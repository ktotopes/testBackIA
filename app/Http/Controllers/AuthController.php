<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Incorrect credentials',
            ], 401);
        }

        $user = auth()->user();

        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            'access_token' => $tokenResult->plainTextToken,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            'access_token' => $tokenResult->plainTextToken,
        ]);
    }
}
