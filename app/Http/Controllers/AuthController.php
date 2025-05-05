<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterInitialRequest;
use App\Http\Requests\RegisterCompleteRequest;
use Illuminate\Support\Facades\DB;
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

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $updateData = $request->validated();

        DB::transaction(function () use ($user, $request, &$updateData) {
            try {
                // Gestion de la photo de profil
                if ($request->hasFile('profile_picture')) {
                    $this->updateProfilePicture($user, $request->file('profile_picture'), $updateData);
                }

                // Gestion du mot de passe si fourni
                if (!empty($updateData['password'])) {
                    $updateData['password'] = Hash::make($updateData['password']);
                } else {
                    unset($updateData['password']);
                }

                // Protection des champs sensibles
                unset($updateData['email']); // L'email ne peut pas être modifié ici
                unset($updateData['role']); // Le rôle ne peut pas être modifié ici
                unset($updateData['status']); // Le statut est géré séparément

                // Mise à jour de l'utilisateur
                $user->update($updateData);

            } catch (\Exception $e) {
                throw $e;
            }
        });

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh(),
        ]);
    }

    protected function updateProfilePicture($user, $file, &$updateData)
    {
        $storage = Storage::disk('public');
        
        // Validation du fichier
        if (!$file->isValid()) {
            throw new \Exception('Invalid profile picture file');
        }

        // Suppression de l'ancienne image
        if ($user->profile_picture && $storage->exists($user->profile_picture)) {
            $storage->delete($user->profile_picture);
        }

        // Enregistrement de la nouvelle image
        $filename = 'user_'.$user->id.'_'.time().'.'.$file->extension();
        $path = $file->storeAs('profile_pictures', $filename, 'public');
        
        $updateData['profile_picture'] = $path;

        // Nettoyage des anciennes images du même utilisateur
        $this->cleanupOldProfilePictures($user->id, $filename);
    }

    protected function cleanupOldProfilePictures($userId, $currentFilename)
    {
        $storage = Storage::disk('public');
        $files = $storage->files('profile_pictures');
        
        foreach ($files as $file) {
            if (str_starts_with($file, "profile_pictures/user_{$userId}_") && 
                $file !== "profile_pictures/{$currentFilename}") {
                try {
                    $storage->delete($file);
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }
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

    public function all(Request $request)
    {
        // Vérification des droits admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::query()
            ->when($request->role, fn($q, $role) => $q->where('role', $role))
            ->when($request->search, fn($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->paginate(10);

        return response()->json([
            'data' => $users,
            'message' => 'Profiles retrieved successfully'
        ]);
    }

    public function deleteProfile(Request $request, $id)
    {
        // Vérification des droits admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $userToDelete = User::findOrFail($id);

        // Empêche l'auto-suppression
        if ($userToDelete->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot delete your own account'], 422);
        }

        // Suppression de la photo de profil si elle existe
        if ($userToDelete->profile_picture) {
            Storage::disk('public')->delete($userToDelete->profile_picture);
        }

        $userToDelete->delete();

        return response()->json([
            'message' => 'Profile deleted successfully'
        ], 204);
    }

    public function getProfile($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'data' => $user,
            'message' => 'Profile retrieved successfully'
        ]);
    }
}