<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
   
     // Enregistrer un nouvel utilisateur
    //  public function register(Request $request)
    //  {
    //      $validator =  Validator::make($request->all(), [
    //          'name' => 'required|string|max:255',
    //          'email' => 'required|string|email|max:255|unique:users',
    //          'password' => 'required|string|min:8|confirmed',
    //      ]);
 
    //      if ($validator->fails()) {
    //          return response()->json($validator->errors(), 400);
    //      }
 
    //      $user = User::create([
    //          'name' => $request->name,
    //          'email' => $request->email,
    //          'password' => Hash::make($request->password),
    //      ]);
 
    //      return response()->json(['message' => 'User registered successfully'], 201);
    //  }
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
     // Connexion de l'utilisateur
    //  public function login(Request $request)
    //  {
    //      $credentials = $request->only('email', 'password');
 
    //      if (Auth::attempt($credentials)) {
    //          $user = Auth::user();
    //          $token = $user->createToken('App Name')->plainTextToken;
 
    //          return response()->json([
    //              'message' => 'Login successful',
    //              'token' => $token
    //          ], 200);
    //      }
 
    //      return response()->json(['message' => 'Unauthorized'], 401);
    //  }
 
    //  // Déconnexion de l'utilisateur
    //  public function logout(Request $request)
    //  {
    //      // Révoquer tous les tokens de l'utilisateur
    //      $request->user()->tokens->each(function ($token) {
    //          $token->delete();
    //      });
 
    //      return response()->json(['message' => 'Successfully logged out'], 200);
    //  }
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
     // Obtenir les informations de l'utilisateur connecté
     public function user(Request $request)
     {
         return response()->json($request->user());
     }

    
}
