<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function register(RegisterRequest $request): UserResource
    {

        $user = User::create(
            $request->validated()
        );

        $token = $user->createToken('usertoken')->plainTextToken;
        return UserResource::make($user)->additional(['token' => $token]);
    }

    public function login(LoginRequest $request): UserResource|JsonResponse
    {
        $fields = $request->validated();

        $user = User::where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'Email Or Password Is Wrong',
            ], 401);
        };

        $token = $user->createToken('usertoken')->plainTextToken;
        return UserResource::make($user)->additional(['token' => $token]);

    }


    public function logout(Request $request): array
    {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged out',
        ];
    }
}
