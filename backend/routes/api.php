<?php

use App\Http\Controllers\Api\Admin\ProductManagementController;
use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\Admin\VendorManagementController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\FavoritesController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ShippingAddressController;
use App\Http\Controllers\Api\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Api\Vendor\ClientController as VendorClientController;
use App\Http\Controllers\Api\Vendor\OrderController as VendorOrderController;
use App\Http\Controllers\Api\Vendor\StockController as VendorStockController;

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

    // Routes Favorites
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoritesController::class, 'index']);
        Route::post('/', [FavoritesController::class, 'store']);
        Route::post('/toggle', [FavoritesController::class, 'toggle']);
        Route::get('/count', [FavoritesController::class, 'count']);
        Route::get('/{productId}/check', [FavoritesController::class, 'isFavorite']);
        Route::delete('/{favoriteId}', [FavoritesController::class, 'destroy']);
        Route::delete('/', [FavoritesController::class, 'clearAll']);
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
        // Products Management
        Route::prefix('products')->group(function () {
            Route::get('/', [VendorProductController::class, 'index']);
            Route::post('/', [VendorProductController::class, 'store']);
            Route::get('/categories-list', [VendorProductController::class, 'categories']);
            Route::get('/statistics', [VendorProductController::class, 'statistics']);
            Route::get('/{id}', [VendorProductController::class, 'show']);
            Route::put('/{id}', [VendorProductController::class, 'update']);
            Route::delete('/{id}', [VendorProductController::class, 'destroy']);
            Route::post('/bulk-status', [VendorProductController::class, 'bulkUpdateStatus']);
            Route::post('/{id}/stock', [VendorProductController::class, 'updateStock']);
        });

        // Clients Tracking
        Route::prefix('clients')->group(function () {
            Route::get('/', [VendorClientController::class, 'index']);
            Route::get('/statistics', [VendorClientController::class, 'statistics']);
            Route::get('/recent-orders', [VendorClientController::class, 'recentOrders']);
            Route::get('/export', [VendorClientController::class, 'exportClients']);
            Route::get('/{clientId}', [VendorClientController::class, 'show']);
        });

        // Orders Tracking
        Route::prefix('orders')->group(function () {
            Route::get('/', [VendorOrderController::class, 'index']);
            Route::get('/statistics', [VendorOrderController::class, 'statistics']);
            Route::get('/status/{status}', [VendorOrderController::class, 'byStatus']);
            Route::get('/export', [VendorOrderController::class, 'export']);
            Route::get('/{orderId}', [VendorOrderController::class, 'show']);
            Route::put('/{orderId}/status', [VendorOrderController::class, 'updateStatus']);
        });

        // Stock Management
        Route::prefix('stock')->group(function () {
            Route::get('/', [VendorStockController::class, 'index']);
            Route::get('/statistics', [VendorStockController::class, 'statistics']);
            Route::get('/low-stock', [VendorStockController::class, 'lowStock']);
            Route::get('/out-of-stock', [VendorStockController::class, 'outOfStock']);
            Route::get('/alerts', [VendorStockController::class, 'alerts']);
            Route::get('/history', [VendorStockController::class, 'history']);
            Route::get('/export', [VendorStockController::class, 'export']);
            Route::put('/{productId}', [VendorStockController::class, 'update']);
            Route::post('/{productId}/adjust', [VendorStockController::class, 'adjust']);
            Route::post('/bulk-update', [VendorStockController::class, 'bulkUpdate']);
        });
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