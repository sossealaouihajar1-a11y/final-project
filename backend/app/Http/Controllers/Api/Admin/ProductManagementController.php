<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\VintageProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductManagementController extends Controller
{
    /**
     * Liste tous les produits avec pagination
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $category = $request->get('category');
        $search = $request->get('search');
        $status = $request->get('status');

        $query = VintageProduct::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('status', 'active');
        } elseif ($status === 'inactive') {
            $query->whereIn('status', ['inactive', 'pending', 'sold_out']);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(VintageProduct $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * Créer un nouveau produit
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|max:100',
                'condition' => 'required|in:neuf,excellent,tres_bon,bon,acceptable,a_restaurer',
                'price' => 'required|numeric|min:0',
                'promotion' => 'sometimes|numeric|min:0|max:100',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            // Log incoming data
            \Log::info('Product creation request', [
                'fields' => array_keys($validated),
                'has_image' => $request->hasFile('image'),
                'image_mime' => $request->file('image')?->getMimeType(),
                'image_size' => $request->file('image')?->getSize(),
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $validated['image_url'] = '/storage/' . $imagePath;
            }

            // Set default status to active
            if (!isset($validated['status'])) {
                $validated['status'] = 'active';
            }

            // Set vendor_id to current admin user for admin-created products
            if (!isset($validated['vendeur_id'])) {
                $validated['vendeur_id'] = auth()->id();
            }

            $product = VintageProduct::create($validated);

            return response()->json([
                'message' => 'Produit créé avec succès',
                'product' => $product,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Product validation error', $e->errors());
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Product creation error', ['message' => $e->getMessage()]);
            return response()->json([
                'message' => 'Erreur lors de la création: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, VintageProduct $product): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category' => 'sometimes|string|max:100',
            'condition' => 'sometimes|in:neuf,excellent,tres_bon,bon,acceptable,a_restaurer',
            'price' => 'sometimes|numeric|min:0',
            'promotion' => 'sometimes|numeric|min:0|max:100',
            'stock' => 'sometimes|integer|min:0',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'sometimes|in:active,inactive,sold_out,pending',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Produit mis à jour avec succès',
            'product' => $product->fresh(),
        ]);
    }

    /**
     * Supprimer un produit
     */
    public function destroy(VintageProduct $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès',
        ]);
    }

    /**
     * Activer/Désactiver un produit
     */
    public function toggleActive(VintageProduct $product): JsonResponse
    {
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);

        return response()->json([
            'message' => $newStatus === 'active' ? 'Produit activé' : 'Produit désactivé',
            'product' => $product->fresh(),
        ]);
    }

    /**
     * Obtenir les statistiques des produits
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_products' => VintageProduct::count(),
            'active_products' => VintageProduct::where('status', 'active')->count(),
            'inactive_products' => VintageProduct::whereIn('status', ['inactive', 'pending', 'sold_out'])->count(),
            'total_stock' => VintageProduct::sum('stock'),
            'total_value' => VintageProduct::sum('price'),
            'categories' => VintageProduct::distinct('category')->pluck('category'),
        ];

        return response()->json($stats);
    }

    /**
     * Obtenir les catégories disponibles
     */
    public function categories(): JsonResponse
    {
        $categories = VintageProduct::distinct('category')
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return response()->json($categories);
    }
}
