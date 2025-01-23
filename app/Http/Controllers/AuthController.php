<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate token for the newly registered user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token, // Return the token heres
        ], 201);
    }

    public function login(Request $request)
    {
        // Extract the email and password from the request
        $credentials = $request->only('email', 'password');
    
        // Check if the email belongs to a coach
        $coach = User::where('email', $credentials['email'])->first();
    
        if ($coach) {
            // If it's a coach, authenticate as coach
            if (Hash::check($credentials['password'], $coach->password)) {
                // Generate a token for the coach
                $token = $coach->createToken('CoachApp')->plainTextToken;
    
                return response()->json([
                    'token' => $token,
                    'message' => $coach->name .' Loggin Successfully.',
                ], 200);
            }
    
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Check if the email belongs to a client
        $client = Client::where('email', $credentials['email'])->first();
    
        if ($client) {
            // If it's a client, authenticate as client

          
            if ($client && Hash::check($request->password, $client->password)) {

          
                // Generate a token for the client
                $token = $client->createToken('ClientApp')->plainTextToken;
    
                return response()->json([
                    'token' => $token,
                    'role' => 'client',
                    'coach_id' => $client->coach_id,  // Pass coach_id if needed
                ], 200);
            }
    
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        return response()->json(['message' => 'User not found'], 404);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        $message = $request->user()->name . ' Logged out successfully.';

        return response()->json(['message' => $message]);
    }

}
