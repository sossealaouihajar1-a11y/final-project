<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\VintageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoritesController extends Controller
{
    /**
     * Get user's favorites
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $favorites = Favorite::where('user_id', $user->id)
            ->with('vintageProduct')
            ->get()
            ->map(function ($favorite) {
                return [
                    'id' => $favorite->id,
                    'product_id' => $favorite->vintage_product_id,
                    'product' => $favorite->vintageProduct ? [
                        'id' => $favorite->vintageProduct->id,
                        'title' => $favorite->vintageProduct->title,
                        'description' => $favorite->vintageProduct->description,
                        'price' => $favorite->vintageProduct->price,
                        'image' => $favorite->vintageProduct->image,
                        'stock' => $favorite->vintageProduct->stock,
                        'category' => $favorite->vintageProduct->category,
                        'condition' => $favorite->vintageProduct->condition,
                        'vendeur_id' => $favorite->vintageProduct->vendeur_id,
                    ] : null,
                    'created_at' => $favorite->created_at,
                ];
            });

        return response()->json([
            'message' => 'Favorites retrieved successfully',
            'data' => $favorites,
        ], 200);
    }

    /**
     * Add a product to favorites
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|uuid|exists:vintage_products,id',
        ]);

        $user = $request->user();
        $product_id = $request->product_id;

        // Check if product exists
        $product = VintageProduct::findOrFail($product_id);

        // Check if already favorited (excluding soft-deleted)
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('vintage_product_id', $product_id)
            ->whereNull('deleted_at')
            ->first();

        if ($existingFavorite) {
            return response()->json([
                'message' => 'This product is already in your favorites',
            ], 400);
        }

        // Check for soft-deleted favorite and restore it
        $softDeletedFavorite = Favorite::withTrashed()
            ->where('user_id', $user->id)
            ->where('vintage_product_id', $product_id)
            ->whereNotNull('deleted_at')
            ->first();

        if ($softDeletedFavorite) {
            $softDeletedFavorite->restore();
            $favorite = $softDeletedFavorite;
        } else {
            // Create new favorite
            $favorite = Favorite::create([
                'user_id' => $user->id,
                'vintage_product_id' => $product_id,
            ]);
        }

        return response()->json([
            'message' => 'Product added to favorites successfully',
            'data' => [
                'id' => $favorite->id,
                'product_id' => $favorite->vintage_product_id,
                'product' => [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => $product->description,
                    'price' => $product->price,
                    'image' => $product->image,
                    'stock' => $product->stock,
                    'category' => $product->category,
                    'condition' => $product->condition,
                    'vendeur_id' => $product->vendeur_id,
                ],
                'created_at' => $favorite->created_at,
            ],
        ], 201);
    }

    /**
     * Remove a product from favorites
     */
    public function destroy(Request $request, $favoriteId)
    {
        $user = $request->user();

        $favorite = Favorite::where('id', $favoriteId)
            ->where('user_id', $user->id)
            ->first();

        if (!$favorite) {
            return response()->json([
                'message' => 'Favorite not found',
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'message' => 'Product removed from favorites successfully',
        ], 200);
    }

    /**
     * Check if a product is favorited by the user
     */
    public function isFavorite(Request $request, $productId)
    {
        $user = $request->user();

        $isFavorite = Favorite::where('user_id', $user->id)
            ->where('vintage_product_id', $productId)
            ->whereNull('deleted_at')
            ->exists();

        return response()->json([
            'is_favorite' => $isFavorite,
        ], 200);
    }

    /**
     * Toggle favorite status for a product
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|uuid|exists:vintage_products,id',
        ]);

        $user = $request->user();
        $product_id = $request->product_id;

        // Check including soft-deleted records
        $favorite = Favorite::withTrashed()
            ->where('user_id', $user->id)
            ->where('vintage_product_id', $product_id)
            ->first();

        if ($favorite) {
            if ($favorite->trashed()) {
                // Restore the soft-deleted favorite
                $favorite->restore();
                return response()->json([
                    'message' => 'Product added to favorites',
                    'is_favorite' => true,
                    'data' => [
                        'id' => $favorite->id,
                        'product_id' => $favorite->vintage_product_id,
                        'created_at' => $favorite->created_at,
                    ],
                ], 201);
            } else {
                // Soft delete the favorite
                $favorite->delete();
                return response()->json([
                    'message' => 'Product removed from favorites',
                    'is_favorite' => false,
                ], 200);
            }
        } else {
            // Add to favorites
            $newFavorite = Favorite::create([
                'user_id' => $user->id,
                'vintage_product_id' => $product_id,
            ]);

            return response()->json([
                'message' => 'Product added to favorites',
                'is_favorite' => true,
                'data' => [
                    'id' => $newFavorite->id,
                    'product_id' => $newFavorite->vintage_product_id,
                    'created_at' => $newFavorite->created_at,
                ],
            ], 201);
        }
    }

    /**
     * Delete all favorites
     */
    public function clearAll(Request $request)
    {
        $user = $request->user();

        Favorite::where('user_id', $user->id)->delete();

        return response()->json([
            'message' => 'All favorites cleared successfully',
        ], 200);
    }

    /**
     * Get count of favorites
     */
    public function count(Request $request)
    {
        $user = $request->user();

        $count = Favorite::where('user_id', $user->id)->count();

        return response()->json([
            'count' => $count,
        ], 200);
    }
}
