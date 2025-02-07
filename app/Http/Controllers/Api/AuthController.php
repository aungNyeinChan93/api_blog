<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    //register
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create($fields);

        return response()->json([
            'message' => 'success',
            'data' => new RegisterResource($user),
        ], 201);
    }

    //login
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'Your provided crenditial are not correct!',
            ], 403);
        }

        $user->tokens()->delete();   //

        return response()->json([
            'message' => 'success',
            'data' => new LoginResource($user),
        ]);

    }

    // logout
    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'success',
        ]);
    }
}
