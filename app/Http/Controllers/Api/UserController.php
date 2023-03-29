<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Auth;

class UserController extends Controller
{
    public function registration(StoreTaskRequest $request, UserService $userService)
    {
        $validator = $request->validated();

        if (!$validator) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $userService->registerUser(
            $request->input('name'),
            $request->input('email'),
            $request->input('password')
        );
        return response()->json([
            'user' => $user,
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if ($validated) {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid login credentials',
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        // If the request fails validation, return the validation errors
        return response()->json(['errors' => $request->errors()], 422);
    }

    public function logout($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
    public function index()
    {

        $users = User::with('tokens')->get();
        return response()->json([
            'users' => $users,
        ]);
    }
}
