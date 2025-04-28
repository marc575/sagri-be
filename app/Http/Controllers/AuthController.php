<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterInitialRequest;
use App\Http\Requests\RegisterCompleteRequest;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // Étape 1 : Inscription initiale
    public function registerInitial(RegisterInitialRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 0, // Statut en attente de complétion
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->only(['id', 'name', 'email', 'role']),
            'next_step' => 'complete_profile', // Indique la prochaine étape
        ], 201);
    }

    // Étape 2 : Complétion du profil
    public function registerComplete(RegisterCompleteRequest $request)
    {
        $user = $request->user();
        
        // Vérifier que l'utilisateur est bien en statut 'pending'
        if ($user->status !== 0) {
            return response()->json([
                'message' => 'Profile already completed or invalid status'
            ], 400);
        }

        $updateData = $request->validated();
        
        // Gestion de la photo de profil
        if ($request->hasFile('profile_picture')) {
            // Supprime l'ancienne photo si elle existe
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $updateData['profile_picture'] = $path;
        }

        $user->update(array_merge($updateData, [
            'status' => 1 // Passage en statut actif
        ]));

        return response()->json([
            'message' => 'Profile completed successfully',
            'user' => $user->fresh(), // Recharge les données fraîches
        ]);
    }
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }
        
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return response()->json(['message' => 'Password changed successfully']);
    }
}