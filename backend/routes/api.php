<?php

use App\Http\Controllers\Api\Admin\VendorManagementController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProductController;
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