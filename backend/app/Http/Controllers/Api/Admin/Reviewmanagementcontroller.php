<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewManagementController extends Controller
{
    /**
     * Récupérer tous les commentaires
     */
    public function index(Request $request)
    {
        try {
            \Log::info('📝 Loading reviews for admin', ['request' => $request->all()]);

            $query = Review::with(['user', 'vintageProduct'])
                ->recent();

            // Filtre par recherche
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('user', function($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                    })->orWhereHas('vintageProduct', function($subQ) use ($search) {
                        $subQ->where('title', 'like', "%{$search}%");
                    })->orWhere('comment', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 20);
            $reviews = $query->paginate($perPage);

            \Log::info('✅ Reviews found', ['total' => $reviews->total(), 'count' => $reviews->count()]);

            // Formater les données
            $reviews->getCollection()->transform(function ($review) {
                return [
                    'id' => $review->id,
                    'user_name' => $review->user->name ?? 'Utilisateur supprimé',
                    'user_email' => $review->user->email ?? 'N/A',
                    'product_name' => $review->vintageProduct->title ?? 'Produit supprimé',
                    'product_id' => $review->vintageProduct->id ?? null,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at,
                    'updated_at' => $review->updated_at,
                ];
            });

            return response()->json($reviews);
        } catch (\Exception $e) {
            \Log::error('❌ Error loading reviews:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Erreur lors de la récupération des commentaires',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les statistiques des commentaires
     */
    public function statistics()
    {
        $stats = [
            'total_reviews' => Review::count(),
            'reviews_this_month' => Review::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'reviews_today' => Review::whereDate('created_at', now()->toDateString())
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Supprimer un commentaire (soft delete)
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json([
            'message' => 'Commentaire supprimé avec succès'
        ]);
    }

    /**
     * Récupérer un commentaire spécifique
     */
    public function show($id)
    {
        $review = Review::with(['user', 'vintageProduct'])->findOrFail($id);

        return response()->json([
            'id' => $review->id,
            'user_name' => $review->user->name ?? 'Utilisateur supprimé',
            'user_email' => $review->user->email ?? 'N/A',
            'product_name' => $review->vintageProduct->title ?? 'Produit supprimé',
            'product_id' => $review->vintageProduct->id ?? null,
            'comment' => $review->comment,
            'created_at' => $review->created_at,
            'updated_at' => $review->updated_at,
        ]);
    }
}