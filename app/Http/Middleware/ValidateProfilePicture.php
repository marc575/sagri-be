<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateProfilePicture
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si un fichier a été uploadé
        if ($request->hasFile('profile_picture')) {
            try {
                $request->validate([
                    'profile_picture' => [
                        'required',
                        'image',
                        'mimes:jpeg,png,jpg,webp',
                        'max:2048', // 2MB maximum
                        'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
                    ],
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Profile picture validation failed', [
                    'error' => $e->getMessage(),
                    'file' => $request->file('profile_picture')->getClientOriginalName(),
                    'size' => $request->file('profile_picture')->getSize(),
                    'mime' => $request->file('profile_picture')->getMimeType(),
                ]);

                return response()->json([
                    'message' => 'Invalid profile picture',
                    'errors' => [
                        'profile_picture' => [
                            'The profile picture must be a valid image (JPEG, PNG, JPG, WEBP)',
                            'Max size: 2MB',
                            'Min dimensions: 100x100px',
                            'Max dimensions: 2000x2000px',
                        ],
                    ],
                ], 422);
            }
        }

        return $next($request);
    }
}