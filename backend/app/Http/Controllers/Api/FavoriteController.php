<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\VintageProduct;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Récupérer tous les favoris de l'utilisateur
     */
    public function index(Request $request)
    {
        $favorites = Favorite::with('vintageProduct')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($favorites);
    }

    /**
     * Ajouter un produit aux favoris
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|uuid|exists:vintage_products,id'
        ]);

        // Vérifier si déjà en favori
        $existing = Favorite::where('user_id', $request->user()->id)
            ->where('vintage_product_id', $request->product_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Ce produit est déjà dans vos favoris',
                'favorite' => $existing
            ], 200);
        }

        // Créer le favori
        $favorite = Favorite::create([
            'user_id' => $request->user()->id,
            'vintage_product_id' => $request->product_id
        ]);

        $favorite->load('vintageProduct');

        return response()->json([
            'message' => 'Produit ajouté aux favoris',
            'favorite' => $favorite
        ], 201);
    }

    /**
     * Retirer un produit des favoris
     */
    public function destroy(Request $request, $productId)
    {
        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('vintage_product_id', $productId)
            ->first();

        if (!$favorite) {
            return response()->json([
                'message' => 'Ce produit n\'est pas dans vos favoris'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'message' => 'Produit retiré des favoris'
        ]);
    }

    /**
     * Vérifier si un produit est en favori
     */
    public function check(Request $request, $productId)
    {
        $isFavorite = Favorite::where('user_id', $request->user()->id)
            ->where('vintage_product_id', $productId)
            ->exists();

        return response()->json([
            'is_favorite' => $isFavorite
        ]);
    }

    /**
     * Récupérer les IDs des produits favoris
     */
    public function getFavoriteIds(Request $request)
    {
        $favoriteIds = Favorite::where('user_id', $request->user()->id)
            ->pluck('vintage_product_id')
            ->toArray();

        return response()->json([
            'favorite_ids' => $favoriteIds
        ]);
    }
}