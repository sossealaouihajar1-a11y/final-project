<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\VintageProduct;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Récupérer tous les avis d'un produit (PUBLIC)
     */
    public function index($productId)
    {
        $reviews = Review::with('user:id,name')
            ->where('vintage_product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'reviews' => $reviews,
            'total' => $reviews->count(),
        ]);
    }

    /**
     * Créer un avis (CLIENT SEULEMENT)
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|uuid|exists:vintage_products,id',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $user = $request->user();

        // Vérifier que l'utilisateur est un client
        if ($user->role !== 'client') {
            return response()->json([
                'message' => 'Seuls les clients peuvent laisser des avis'
            ], 403);
        }

        // Vérifier s'il existe déjà un avis
        $existing = Review::where('user_id', $user->id)
            ->where('vintage_product_id', $request->product_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Vous avez déjà laissé un avis pour ce produit. Vous pouvez le modifier ou le supprimer.'
            ], 400);
        }

        // Créer l'avis
        $review = Review::create([
            'user_id' => $user->id,
            'vintage_product_id' => $request->product_id,
            'comment' => $request->comment,
        ]);

        $review->load('user:id,name');

        return response()->json([
            'message' => 'Avis ajouté avec succès',
            'review' => $review,
        ], 201);
    }

    /**
     * Mettre à jour un avis
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review = Review::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $review->update([
            'comment' => $request->comment,
        ]);

        $review->load('user:id,name');

        return response()->json([
            'message' => 'Avis mis à jour avec succès',
            'review' => $review,
        ]);
    }

    /**
     * Supprimer un avis
     */
    public function destroy(Request $request, $id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $review->delete();

        return response()->json([
            'message' => 'Avis supprimé avec succès',
        ]);
    }

    /**
     * Vérifier si l'utilisateur a déjà laissé un avis
     */
    public function checkUserReview(Request $request, $productId)
    {
        $review = Review::where('user_id', $request->user()->id)
            ->where('vintage_product_id', $productId)
            ->with('user:id,name')
            ->first();

        return response()->json([
            'has_reviewed' => $review !== null,
            'review' => $review
        ]);
    }

    /**
 * Récupérer tous les avis de l'utilisateur connecté
 */
public function myReviews(Request $request)
{
    $reviews = Review::with(['user:id,name', 'vintageProduct:id,title,image_url,price,category'])
        ->where('user_id', $request->user()->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'reviews' => $reviews,
        'total' => $reviews->count(),
    ]);
}
}