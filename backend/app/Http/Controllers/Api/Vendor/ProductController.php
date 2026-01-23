<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VintageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Get all products for the authenticated vendor
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = VintageProduct::where('vendeur_id', $user->id);

        // Filters
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('category') && !empty($request->input('category'))) {
            $query->where('category', $request->input('category'));
        }

        if ($request->has('status') && !empty($request->input('status'))) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('condition') && !empty($request->input('condition'))) {
            $query->where('condition', $request->input('condition'));
        }

        // Pagination
        $perPage = $request->input('per_page', 15);
        $products = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Transform image URLs to full URLs
        $products->getCollection()->transform(function ($product) {
            if ($product->image_url && !filter_var($product->image_url, FILTER_VALIDATE_URL)) {
                $product->image_url = url($product->image_url);
            }
            return $product;
        });

        return response()->json($products);
    }

    /**
     * Get product statistics for the vendor
     */
    public function statistics()
    {
        $user = Auth::user();
        $products = VintageProduct::where('vendeur_id', $user->id);

        $stats = [
            'total_products' => $products->count(),
            'active_products' => $products->where('status', 'active')->count(),
            'low_stock' => $products->where('stock', '<', 5)->count(),
            'out_of_stock' => $products->where('stock', 0)->count(),
            'total_revenue' => 0, // Will be calculated from orders
        ];

        // Calculate revenue from orders
        $revenue = \DB::table('order_items')
            ->join('vintage_products', 'order_items.vintage_product_id', '=', 'vintage_products.id')
            ->where('vintage_products.vendeur_id', $user->id)
            ->selectRaw('SUM(order_items.price * order_items.quantity) as total')
            ->value('total') ?? 0;
        $stats['total_revenue'] = $revenue;

        return response()->json($stats);
    }

    /**
     * Get categories list from database enum
     */
    public function categories()
    {
        try {
            // Récupérer depuis l'enum de la base de données
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
                    
                    \Log::info('✅ Catégories trouvées:', $categories);
                    
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
     * Create a new product
     */
    public function store(Request $request)
    {
        try {
            \Log::info('📦 Product creation request received', [
                'data' => $request->except(['image']),
                'has_image' => $request->hasFile('image')
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|in:mode,mobilier,accessoires,electronique_vintage,art,autre',
                'price' => 'required|numeric|min:0',
                'promotion' => 'nullable|numeric|min:0|max:100',
                'condition' => 'required|in:neuf,excellent,tres_bon,bon,acceptable,a_restaurer',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'status' => 'nullable|in:active,inactive,sold_out,pending',
            ]);

            $validated['vendeur_id'] = Auth::id();
            $validated['status'] = $validated['status'] ?? 'active';
            $validated['promotion'] = $validated['promotion'] ?? 0;

            // Handle image upload - save to public/images
            if ($request->hasFile('image')) {
                try {
                    $file = $request->file('image');
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Ensure the directory exists
                    $imagesDir = public_path('images');
                    if (!file_exists($imagesDir)) {
                        mkdir($imagesDir, 0755, true);
                    }
                    
                    // Move file to public/images
                    if ($file->move($imagesDir, $filename)) {
                        // Return full URL for the image
                        $validated['image_url'] = url('/images/' . $filename);
                        \Log::info('✅ Image uploaded successfully:', ['path' => $validated['image_url']]);
                    } else {
                        \Log::error('❌ Failed to move image file');
                        return response()->json([
                            'message' => 'Erreur lors de l\'upload de l\'image',
                        ], 500);
                    }
                } catch (\Exception $e) {
                    \Log::error('❌ Image upload error:', ['message' => $e->getMessage()]);
                    return response()->json([
                        'message' => 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage(),
                    ], 500);
                }
            }

            $product = VintageProduct::create($validated);

            // Transform image URL to full URL if needed
            if ($product->image_url && !filter_var($product->image_url, FILTER_VALIDATE_URL)) {
                $product->image_url = url($product->image_url);
            }

            \Log::info('✅ Product created successfully:', ['id' => $product->id]);

            return response()->json([
                'message' => 'Produit créé avec succès',
                'product' => $product
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('❌ Product validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('❌ Product creation error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Erreur lors de la création du produit: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific product
     */
    public function show($id)
    {
        $user = Auth::user();
        $product = VintageProduct::where('vendeur_id', $user->id)
            ->findOrFail($id);

        // Transform image URL to full URL if needed
        if ($product->image_url && !filter_var($product->image_url, FILTER_VALIDATE_URL)) {
            $product->image_url = url($product->image_url);
        }

        return response()->json($product);
    }

    /**
     * Update a product
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $product = VintageProduct::where('vendeur_id', $user->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'promotion' => 'nullable|numeric|min:0|max:100',
            'condition' => 'nullable|in:neuf,excellent,tres_bon,bon,acceptable,a_restaurer',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'nullable|in:active,inactive,sold_out,pending',
        ]);

        // Handle image upload - save to public/images
        if ($request->hasFile('image')) {
            try {
                // Delete old image if exists
                if ($product->image_url) {
                    // Extract filename from full URL or relative path
                    $oldImagePath = str_replace(url('/'), '', $product->image_url);
                    $oldImageFullPath = public_path($oldImagePath);
                    if (file_exists($oldImageFullPath)) {
                        unlink($oldImageFullPath);
                    }
                }
                
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Ensure the directory exists
                $imagesDir = public_path('images');
                if (!file_exists($imagesDir)) {
                    mkdir($imagesDir, 0755, true);
                }
                
                // Move file to public/images
                if ($file->move($imagesDir, $filename)) {
                    // Return full URL for the image
                    $validated['image_url'] = url('/images/' . $filename);
                    \Log::info('✅ Image updated successfully:', ['path' => $validated['image_url']]);
                } else {
                    \Log::error('❌ Failed to move image file');
                }
            } catch (\Exception $e) {
                \Log::error('❌ Image update error:', ['message' => $e->getMessage()]);
            }
        }

        $product->update(array_filter($validated));

        // Refresh product to get updated data
        $product->refresh();

        // Transform image URL to full URL if needed
        if ($product->image_url && !filter_var($product->image_url, FILTER_VALIDATE_URL)) {
            $product->image_url = url($product->image_url);
        }

        return response()->json($product);
    }

    /**
     * Delete a product
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $product = VintageProduct::where('vendeur_id', $user->id)
            ->findOrFail($id);

        $product->delete();

        return response()->json(['message' => 'Produit supprimé avec succès'], 200);
    }

    /**
     * Bulk update product status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'status' => 'required|in:active,inactive,sold_out',
        ]);

        $user = Auth::user();
        VintageProduct::where('vendeur_id', $user->id)
            ->whereIn('id', $validated['ids'])
            ->update(['status' => $validated['status']]);

        return response()->json(['message' => 'Statuts mis à jour avec succès'], 200);
    }

    /**
     * Update stock for multiple products
     */
    public function updateStock(Request $request, $id)
    {
        $user = Auth::user();
        $product = VintageProduct::where('vendeur_id', $user->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $product->update(['stock' => $validated['stock']]);

        return response()->json($product);
    }
}
