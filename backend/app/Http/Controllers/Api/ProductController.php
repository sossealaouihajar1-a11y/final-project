<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VintageProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Afficher tous les produits actifs des vendeurs approuvés
     */
    public function index(Request $request)
    {
        $query = VintageProduct::with('vendeur')
            ->active()
            ->inStock()
            ->whereHas('vendeur', function ($q) {
                $q->where('role', 'vendeur')
                  ->where('vendor_status', 'approved');
            });

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filtre par condition
        if ($request->filled('condition')) {
            $query->byCondition($request->condition);
        }

        // Filtre par prix min/max
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filtre promotion
        if ($request->boolean('with_promotion')) {
            $query->withPromotion();
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        
        if ($sortBy === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sortBy === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sortBy === 'title') {
            $query->orderBy('title', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Afficher un produit spécifique
     */
    public function show($id)
    {
        $product = VintageProduct::with(['vendeur'])
            ->where('id', $id)
            ->active()
            ->whereHas('vendeur', function ($q) {
                $q->where('role', 'vendeur')
                  ->where('vendor_status', 'approved');
            })
            ->firstOrFail();

        return response()->json($product);
    }

    /**
     * Récupérer toutes les catégories disponibles
     */
    public function categories()
    {
        $categories = VintageProduct::active()
            ->whereHas('vendeur', function ($q) {
                $q->where('vendor_status', 'approved');
            })
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return response()->json($categories);
    }

    /**
     * Statistiques des produits
     */
    public function stats()
    {
        $activeProducts = VintageProduct::active()
            ->inStock()
            ->whereHas('vendeur', function ($q) {
                $q->where('vendor_status', 'approved');
            });

        $stats = [
            'total' => $activeProducts->count(),
            'categories' => $activeProducts->distinct('category')->count('category'),
            'avg_price' => round($activeProducts->avg('price'), 2),
            'with_promotion' => $activeProducts->where('promotion', '>', 0)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Produits en promotion
     */
    public function promotions()
    {
        $products = VintageProduct::with('vendeur')
            ->active()
            ->inStock()
            ->withPromotion()
            ->whereHas('vendeur', function ($q) {
                $q->where('vendor_status', 'approved');
            })
            ->orderBy('promotion', 'desc')
            ->paginate(12);

        return response()->json($products);
    }

    /**
     * Produits par catégorie
     */
    public function byCategory($category)
    {
        $products = VintageProduct::with('vendeur')
            ->active()
            ->inStock()
            ->byCategory($category)
            ->whereHas('vendeur', function ($q) {
                $q->where('vendor_status', 'approved');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json($products);
    }
}