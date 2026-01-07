<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VintageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('condition')) {
            $query->where('condition', $request->input('condition'));
        }

        // Pagination
        $perPage = $request->input('per_page', 15);
        $products = $query->orderBy('created_at', 'desc')->paginate($perPage);

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
            'avg_rating' => 0, // Will be calculated from reviews
        ];

        // Calculate revenue from orders
        $revenue = \DB::table('order_items')
            ->join('vintage_products', 'order_items.vintage_product_id', '=', 'vintage_products.id')
            ->where('vintage_products.vendeur_id', $user->id)
            ->sum('order_items.total_price');
        $stats['total_revenue'] = $revenue;

        // Calculate average rating
        $avgRating = \DB::table('reviews')
            ->join('vintage_products', 'reviews.vintage_product_id', '=', 'vintage_products.id')
            ->where('vintage_products.vendeur_id', $user->id)
            ->avg('reviews.rating');
        $stats['avg_rating'] = round($avgRating ?? 0, 2);

        return response()->json($stats);
    }

    /**
     * Get categories list
     */
    public function categories()
    {
        $categories = VintageProduct::distinct('category')
            ->pluck('category')
            ->filter()
            ->values();

        return response()->json($categories);
    }

    /**
     * Create a new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
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

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $product = VintageProduct::create($validated);

        return response()->json($product, 201);
    }

    /**
     * Get a specific product
     */
    public function show($id)
    {
        $user = Auth::user();
        $product = VintageProduct::where('vendeur_id', $user->id)
            ->findOrFail($id);

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

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $product->update(array_filter($validated));

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
