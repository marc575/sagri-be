<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DocumentController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register/initial', [AuthController::class, 'registerInitial']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/register/complete', [AuthController::class, 'registerComplete']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/projects/all', [ProjectController::class, 'all']);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('activities', ActivityController::class);
    Route::get('/products/all', [ProductController::class, 'all']);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class);
    Route::get('/documents/all', [DocumentController::class, 'all']);
    Route::apiResource('documents', DocumentController::class);
    Route::get('/users', [AuthController::class, 'all']);
    Route::put('/user', [AuthController::class, 'updateProfile']);
    Route::get('/users/{id}', [AuthController::class, 'getProfile']);
    Route::delete('/profiles/{id}', [AuthController::class, 'deleteProfile']);
});

// Password reset routes
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);

Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);