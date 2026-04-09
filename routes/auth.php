<?php

use App\Http\Controllers\Api\Auth\AdminController;
use App\Http\Controllers\Api\Auth\CustomerController;
use App\Http\Controllers\Api\Auth\DeliveryController;
use App\Http\Controllers\Api\Auth\VerifyController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\ResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// ─── Admin ───────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::post('register', [AdminController::class, 'register'])->middleware('throttle:5,1');;
    Route::post('login',    [AdminController::class, 'login'])->middleware('throttle:5,1');;

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('logout', [AdminController::class, 'logout']);
        Route::get('me',       [AdminController::class, 'me']);
        Route::get('allUsers', [AdminController::class, 'allUsers']);

        Route::prefix('profile')->group(function () {
            Route::get('/',               [ProfileController::class, 'show']);
            Route::post('/',              [ProfileController::class, 'update']);
            Route::post('change-password', [ProfileController::class, 'changePassword']);
            Route::delete('avatar',       [ProfileController::class, 'deleteAvatar']);
            Route::delete('account',      [ProfileController::class, 'deleteAccount']);
        });
    });
});

// ─── Customer ─────────────────────────────────────────────
Route::prefix('customer')->group(function () {
    Route::post('register',   [CustomerController::class, 'register'])->middleware('throttle:5,1');
    Route::post('login',      [CustomerController::class, 'login'])->middleware('throttle:5,1');;
    Route::post('verify-otp', [VerifyController::class,  'verify'])->middleware('throttle:5,1');;
    Route::post('resend-otp', [VerifyController::class, 'resendOtp'])->middleware('throttle:3,1');;
    Route::post('forget',     [ResetController::class,   'forgetPassword'])->middleware('throttle:3,1');;
    Route::post('reset',      [ResetController::class,   'ResetPassword'])->middleware('throttle:3,1');;

    Route::middleware(['auth:sanctum', 'customer'])->group(function () {
        Route::post('logout', [CustomerController::class, 'logout']);
        Route::get('me',      [CustomerController::class, 'me']);

        Route::prefix('profile')->group(function () {
            Route::get('/',               [ProfileController::class, 'show']);
            Route::post('/',              [ProfileController::class, 'update']);
            Route::post('change-password', [ProfileController::class, 'changePassword']);
            Route::delete('avatar',       [ProfileController::class, 'deleteAvatar']);
            Route::delete('account',      [ProfileController::class, 'deleteAccount']);
        });
    });
});



// ─── Delivery ─────────────────────────────────────────────
Route::prefix('delivery')->group(function () {
    Route::post('register',   [DeliveryController::class, 'register'])->middleware('throttle:5,1');;
    Route::post('login',      [DeliveryController::class, 'login'])->middleware('throttle:5,1');;
    Route::post('verify-otp', [VerifyController::class,   'verify'])->middleware('throttle:5,1');;
    Route::post('resend-otp', [VerifyController::class, 'resendOtp'])->middleware('throttle:5,1');;
    Route::post('forget',     [ResetController::class,    'forgetPassword'])->middleware('throttle:5,1');;
    Route::post('reset',      [ResetController::class,    'ResetPassword'])->middleware('throttle:5,1');;

    Route::middleware(['auth:sanctum', 'delivery'])->group(function () {
        Route::post('logout', [DeliveryController::class, 'logout']);
        Route::get('me',      [DeliveryController::class, 'me']);

        Route::prefix('profile')->group(function () {
            Route::get('/',               [ProfileController::class, 'show']);
            Route::post('/',              [ProfileController::class, 'update']);
            Route::post('change-password', [ProfileController::class, 'changePassword']);
            Route::delete('avatar',       [ProfileController::class, 'deleteAvatar']);
            Route::delete('account',      [ProfileController::class, 'deleteAccount']);
        });
    });
});
