<?php
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// products
Route::get('products/filter', [ProductController::class, 'filter']);
Route::apiResource('products', ProductController::class)->only([
    'index', 'show'
]);
// products admin only
Route::middleware(['auth:sanctum', 'permission:create products'])->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
});

// archived products admin only
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('products/{product}/restore', [ProductController::class, 'undoDelete'])
        ->middleware('permission:restore products');

    Route::delete('products/{product}/force', [ProductController::class, 'permanentDelete'])
        ->middleware('permission:create products');

    Route::get('admin/products', [ProductController::class, 'adminIndex'])
        ->middleware('permission:create products');
});
// categories admin only
Route::middleware(['auth:sanctum', 'permission:create products'])->group(function () {
    Route::apiResource('categories',CategoryController::class)->except(['index', 'show']);
});
Route::get('/categories/{category}/products', [CategoryController::class, 'products']);







// carts customer and admin only
Route::middleware(['auth:sanctum', 'permission:create orders'])->group(function () {
    Route::apiResource('carts',CartController::class);
});



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    Route::get('/orders', [CheckoutController::class, 'orderHistory']);
    Route::get('/orders/{order}', [CheckoutController::class, 'orderDetails']);
});



// Payment routes (authenticated)
Route::middleware('auth:sanctum')->group(function () {
    // Create payment (Stripe or other providers in the future)
    Route::post('/orders/{order}/payments', [PaymentController::class, 'createPayment']);

    // Confirm payment status
    Route::get('/payments/{paymentId}/confirm', [PaymentController::class, 'confirmPayment']);
});

// Webhook endpoints (no authentication required)
Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook'])
    ->name('webhook.stripe')
    ->withoutMiddleware(['auth:sanctum', 'throttle']);





include_once __DIR__.'/auth.php';
