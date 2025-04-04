<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function Register(RegisterRequest $request)
    {
        $formfield = $request->validated();
        $formfield['password'] = bcrypt($formfield['password']);
        // Create a new user
        $user = User::create($formfield);
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a success response
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    public function index(Request $request)
    {
        // Get all users
        $users = User::all();

        // Return the users as a JSON response
        return response()->json($users, 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                "message" => "User not found"
            ], 401);
        }
        if ($user->password != $request->input('passwoord')) {
            return response()->json([
                "message" => "Password not matched"
            ], 401);
        }
        $user->tokens()->delete();
        // Create a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;


    }
}
