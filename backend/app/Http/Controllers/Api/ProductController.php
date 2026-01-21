<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VintageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Afficher tous les produits actifs des vendeurs approuvés
     */
    public function index(Request $request)
    {
        $query = VintageProduct::with('vendeur')
            ->active()
           
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

        //  FILTRAGE PAR PRIX 
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->min_price;
            $maxPrice = $request->max_price;
            
            if ($minPrice !== null && $maxPrice !== null) {
                // Les deux sont présents - utiliser whereBetween
                $query->whereBetween('price', [(float)$minPrice, (float)$maxPrice]);
            } elseif ($minPrice !== null) {
                // Seulement prix minimum
                $query->where('price', '>=', (float)$minPrice);
            } elseif ($maxPrice !== null) {
                // Seulement prix maximum
                $query->where('price', '<=', (float)$maxPrice);
            }
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
        $perPage = $request->get('per_page', 100);
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
        try {
            // Méthode 1 : Récupérer depuis l'enum de la base de données
            $enumValues = DB::select("
                SHOW COLUMNS 
                FROM vintage_products 
                WHERE Field = 'category'
            ");
            
            if (!empty($enumValues)) {
                $type = $enumValues[0]->Type;
                
                // Log pour debug
                \Log::info('🔍 Enum type trouvé:', ['type' => $type]);
                
                // Parser l'enum : enum('mode','mobilier',...)
                preg_match('/^enum\((.*)\)$/', $type, $matches);
                
                if (!empty($matches[1])) {
                    // Extraire les valeurs
                    $categories = array_map(function($value) {
                        return trim($value, "'");
                    }, explode(',', $matches[1]));
                    
                    \Log::info(' Catégories trouvées:', $categories);
                    
                    return response()->json($categories);
                }
            }
            
            // Si la méthode enum ne fonctionne pas, fallback
            \Log::warning('⚠️ Enum non trouvé, utilisation du fallback');
            
            return response()->json([
                'mode',
                'mobilier',
                'accessoires',
                'electronique_vintage',
                'art',
                'autre'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('❌ Erreur categories:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback en cas d'erreur
            return response()->json([
                'mode',
                'mobilier',
                'accessoires',
                'electronique_vintage',
                'art',
                'autre'
            ]);
        }
    }

    /**
     * ⭐ NOUVEAU: Récupérer toutes les conditions disponibles
     */
/**
 * Récupérer toutes les conditions de l'ENUM
 */
public function conditions()
{
    try {
        // Récupérer la définition de la colonne
        $columnInfo = DB::select("SHOW COLUMNS FROM vintage_products WHERE Field = 'condition'");
        
        if (!empty($columnInfo)) {
            $columnType = $columnInfo[0]->Type;
            
            \Log::info('🔍 Type de colonne:', ['type' => $columnType]);
            
            // Vérifier si c'est un ENUM et extraire les valeurs
            if (preg_match('/^enum\((.*)\)$/i', $columnType, $matches)) {
                
                // Utiliser une regex pour extraire toutes les valeurs entre quotes
                preg_match_all("/'([^']+)'/", $matches[1], $values);
                
                if (!empty($values[1])) {
                    $conditions = array_map('trim', $values[1]);
                    $conditions = array_filter($conditions);
                    $conditions = array_values($conditions);
                    
                    \Log::info('✅ Conditions ENUM:', $conditions);
                    
                    return response()->json($conditions);
                }
            }
        }
        
        // Fallback: valeurs distinctes utilisées
        $usedConditions = VintageProduct::select('condition')
            ->distinct()
            ->whereNotNull('condition')
            ->where('condition', '!=', '')
            ->pluck('condition')
            ->toArray();
        
        if (!empty($usedConditions)) {
            return response()->json($usedConditions);
        }
        
        // Fallback final
        return response()->json(['Excellent', 'Very Good', 'Good', 'Fair']);
        
    } catch (\Exception $e) {
        \Log::error('❌ Erreur conditions:', ['error' => $e->getMessage()]);
        return response()->json(['Excellent', 'Very Good', 'Good', 'Fair']);
    }
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