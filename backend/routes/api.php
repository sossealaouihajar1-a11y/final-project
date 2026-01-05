<?php

use App\Http\Controllers\Api\Admin\ProductManagementController;
use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\Admin\VendorManagementController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ShippingAddressController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::prefix('auth')->group(function () {
    Route::post('/register/client', [AuthController::class, 'registerClient']);
    Route::post('/register/vendor', [AuthController::class, 'registerVendor']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes protégées (authentification requise)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // shipping adress
     Route::get('/shipping-address', [ShippingAddressController::class, 'show']);
    Route::post('/shipping-address', [ShippingAddressController::class, 'store']);
    Route::delete('/shipping-address', [ShippingAddressController::class, 'destroy']);

    // modification de profile
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

    // Routes Panier & Commandes
    Route::post('/cart/checkout', [CartController::class, 'checkout']);
    
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::post('/{id}/cancel', [OrderController::class, 'cancel']);
    });
    // Routes Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::prefix('vendors')->group(function () {
            Route::get('/', [VendorManagementController::class, 'index']);
            Route::get('/pending', [VendorManagementController::class, 'pending']);
            Route::get('/{user}', [VendorManagementController::class, 'show']);
            Route::post('/{user}/approve', [VendorManagementController::class, 'approve']);
            Route::post('/{user}/reject', [VendorManagementController::class, 'reject']);
            Route::post('/{user}/suspend', [VendorManagementController::class, 'suspend']);
            Route::post('/{user}/reactivate', [VendorManagementController::class, 'reactivate']);
        });
        
        // Users management
        Route::prefix('users')->group(function () {
            Route::get('/', [UserManagementController::class, 'index']);
            Route::get('/statistics', [UserManagementController::class, 'statistics']);
            Route::get('/{user}', [UserManagementController::class, 'show']);
            Route::put('/{user}', [UserManagementController::class, 'update']);
            Route::delete('/{user}', [UserManagementController::class, 'destroy']);
            Route::post('/{user}/suspend', [UserManagementController::class, 'suspend']);
            Route::post('/{user}/reactivate', [UserManagementController::class, 'reactivate']);
        });
        
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductManagementController::class, 'index']);
            Route::post('/', [ProductManagementController::class, 'store']);
            Route::get('/statistics', [ProductManagementController::class, 'statistics']);
            Route::get('/categories-list', [ProductManagementController::class, 'categories']);
            Route::get('/{product}', [ProductManagementController::class, 'show']);
            Route::put('/{product}', [ProductManagementController::class, 'update']);
            Route::delete('/{product}', [ProductManagementController::class, 'destroy']);
            Route::post('/{product}/toggle-active', [ProductManagementController::class, 'toggleActive']);
        });

    });

    // Routes Vendeur (approuvé uniquement)
    Route::middleware('vendor.approved')->prefix('vendor')->group(function () {
        // TODO: Routes pour la gestion des produits
    });

    // Routes Client
    Route::middleware('role:client')->prefix('client')->group(function () {
        // TODO: Routes pour le panier, commandes, etc.
    });
});                                                                                                                         

// products
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/categories', [ProductController::class, 'categories']);
    Route::get('/stats', [ProductController::class, 'stats']);
    Route::get('/promotions', [ProductController::class, 'promotions']);
    Route::get('/category/{category}', [ProductController::class, 'byCategory']);
    Route::get('/{id}', [ProductController::class, 'show']);
});