<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VintageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Get stock overview
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = VintageProduct::where('vendeur_id', $user->id);

        // Filter by stock level
        if ($request->has('stock_level')) {
            $level = $request->input('stock_level');
            if ($level === 'low') {
                $query->where('stock', '<', 5)->where('stock', '>', 0);
            } elseif ($level === 'out') {
                $query->where('stock', 0);
            } elseif ($level === 'sufficient') {
                $query->where('stock', '>=', 5);
            }
        }

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        // Sort
        $sortBy = $request->input('sort_by', 'stock');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->select('id', 'title', 'stock', 'category', 'price', 'status', 'updated_at')
            ->paginate($request->input('per_page', 20));

        return response()->json($products);
    }

    /**
     * Get stock statistics
     */
    public function statistics()
    {
        $user = Auth::user();
        $products = VintageProduct::where('vendeur_id', $user->id);

        $stats = [
            'total_items' => $products->sum('stock'),
            'total_products' => $products->count(),
            'low_stock_products' => $products->where('stock', '<', 5)->where('stock', '>', 0)->count(),
            'out_of_stock_products' => $products->where('stock', 0)->count(),
            'sufficient_stock_products' => $products->where('stock', '>=', 5)->count(),
            'inventory_value' => 0,
        ];

        // Calculate inventory value
        $inventoryValue = \DB::table('vintage_products')
            ->where('vendeur_id', $user->id)
            ->select(\DB::raw('SUM(price * stock) as value'))
            ->first();
        $stats['inventory_value'] = round($inventoryValue->value ?? 0, 2);

        return response()->json($stats);
    }

    /**
     * Get low stock products
     */
    public function lowStock()
    {
        $user = Auth::user();

        $products = VintageProduct::where('vendeur_id', $user->id)
            ->where('stock', '<', 5)
            ->where('stock', '>', 0)
            ->select('id', 'title', 'stock', 'category', 'price')
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json($products);
    }

    /**
     * Get out of stock products
     */
    public function outOfStock()
    {
        $user = Auth::user();

        $products = VintageProduct::where('vendeur_id', $user->id)
            ->where('stock', 0)
            ->select('id', 'title', 'category', 'price', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return response()->json($products);
    }

    /**
     * Update product stock
     */
    public function update(Request $request, $productId)
    {
        $user = Auth::user();

        $product = VintageProduct::where('vendeur_id', $user->id)
            ->findOrFail($productId);

        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'reason' => 'nullable|in:restock,sale,damage,return,correction',
            'notes' => 'nullable|string',
        ]);

        $oldStock = $product->stock;
        $newStock = $validated['stock'];
        $difference = $newStock - $oldStock;

        $product->update(['stock' => $newStock]);

        // Log stock movement
        $this->logStockMovement($product->id, $difference, $validated['reason'] ?? 'manual', $validated['notes'] ?? '');

        return response()->json([
            'product' => $product,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'difference' => $difference,
        ]);
    }

    /**
     * Bulk update stock
     */
    public function bulkUpdate(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|string',
            'products.*.stock' => 'required|integer|min:0',
        ]);

        $updatedProducts = [];

        foreach ($validated['products'] as $item) {
            $product = VintageProduct::where('vendeur_id', $user->id)
                ->find($item['id']);

            if ($product) {
                $oldStock = $product->stock;
                $product->update(['stock' => $item['stock']]);
                $this->logStockMovement($product->id, $item['stock'] - $oldStock, 'bulk_update', '');
                $updatedProducts[] = $product;
            }
        }

        return response()->json([
            'message' => count($updatedProducts) . ' produits mis à jour',
            'products' => $updatedProducts,
        ]);
    }

    /**
     * Get stock movement history
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $productId = $request->query('product_id');

        $query = DB::table('stock_movements')
            ->join('vintage_products', 'stock_movements.product_id', '=', 'vintage_products.id')
            ->where('vintage_products.vendeur_id', $user->id)
            ->select('stock_movements.*', 'vintage_products.title');

        if ($productId) {
            $query->where('vintage_products.id', $productId);
        }

        $movements = $query->orderBy('stock_movements.created_at', 'desc')
            ->paginate(20);

        return response()->json($movements);
    }

    /**
     * Adjust stock
     */
    public function adjust(Request $request, $productId)
    {
        $user = Auth::user();

        $product = VintageProduct::where('vendeur_id', $user->id)
            ->findOrFail($productId);

        $validated = $request->validate([
            'adjustment' => 'required|integer',
            'reason' => 'required|in:restock,damage,correction,return,other',
            'notes' => 'nullable|string',
        ]);

        $oldStock = $product->stock;
        $newStock = max(0, $oldStock + $validated['adjustment']);

        $product->update(['stock' => $newStock]);

        $this->logStockMovement(
            $product->id,
            $validated['adjustment'],
            $validated['reason'],
            $validated['notes'] ?? ''
        );

        return response()->json([
            'product' => $product,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'adjustment' => $validated['adjustment'],
        ]);
    }

    /**
     * Get expiration alerts (if applicable)
     */
    public function alerts()
    {
        $user = Auth::user();

        $alerts = [
            'low_stock' => VintageProduct::where('vendeur_id', $user->id)
                ->where('stock', '<', 5)
                ->where('stock', '>', 0)
                ->count(),
            'out_of_stock' => VintageProduct::where('vendeur_id', $user->id)
                ->where('stock', 0)
                ->count(),
        ];

        return response()->json($alerts);
    }

    /**
     * Log stock movement (helper method)
     */
    private function logStockMovement($productId, $quantity, $reason, $notes)
    {
        DB::table('stock_movements')->insert([
            'product_id' => $productId,
            'quantity' => $quantity,
            'reason' => $reason,
            'notes' => $notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Export stock report
     */
    public function export()
    {
        $user = Auth::user();

        $products = VintageProduct::where('vendeur_id', $user->id)
            ->select('id', 'title', 'category', 'stock', 'price', 'status', 'updated_at')
            ->orderBy('title')
            ->get();

        $csv = "ID,Produit,Catégorie,Stock,Prix,Statut,Dernière mise à jour\n";
        foreach ($products as $product) {
            $inventoryValue = $product->stock * $product->price;
            $csv .= "{$product->id},{$product->title},{$product->category},{$product->stock}," 
                  . number_format($product->price, 2) . ",{$product->status},{$product->updated_at}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="stock-' . date('Y-m-d') . '.csv"');
    }
}
