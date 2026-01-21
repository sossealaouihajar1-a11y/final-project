<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Get all orders containing products from this vendor
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Order::whereHas('orderItems', function ($q) use ($user) {
            $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                $subQ->where('vendeur_id', $user->id);
            });
        })->with(['user' => function ($q) {
            $q->select('id', 'name', 'email', 'phone');
        }, 'orderItems' => function ($q) use ($user) {
            $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                $subQ->where('vendeur_id', $user->id);
            })->with('vintageProduct');
        }, 'shippingAddress']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));


        return response()->json($orders);
    }

    /**
     * Get order details
     */
    public function show($orderId)
    {
        $user = Auth::user();

        $order = Order::whereHas('orderItems', function ($q) use ($user) {
            $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                $subQ->where('vendeur_id', $user->id);
            });
        })->with(['user', 'orderItems' => function ($q) use ($user) {
            $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                $subQ->where('vendeur_id', $user->id);
            })->with('vintageProduct');
        }, 'shippingAddress', 'payments'])
        ->findOrFail($orderId);

        return response()->json($order);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $orderId)
    {
        $user = Auth::user();

        $order = Order::whereHas('orderItems', function ($q) use ($user) {
            $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                $subQ->where('vendeur_id', $user->id);
            });
        })->findOrFail($orderId);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json($order);
    }

    /**
     * Get orders statistics
     */
    public function statistics()
    {
        $user = Auth::user();

        $orders = Order::whereHas('orderItems', function ($q) use ($user) {
            $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                $subQ->where('vendeur_id', $user->id);
            });
        })->get();

        $stats = [
            'total_orders' => $orders->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'confirmed_orders' => $orders->where('status', 'confirmed')->count(),
            'shipped_orders' => $orders->where('status', 'shipped')->count(),
            'delivered_orders' => $orders->where('status', 'delivered')->count(),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            'total_items_sold' => 0,
            'total_revenue' => 0,
        ];

        // Calculate revenue and items sold
        $details = \DB::table('order_items')
            ->join('vintage_products', 'order_items.vintage_product_id', '=', 'vintage_products.id')
            ->where('vintage_products.vendeur_id', $user->id)
            ->select(\DB::raw('COUNT(*) as items_count'), \DB::raw('SUM(order_items.price * order_items.quantity) as revenue'))
            ->first();

        $stats['total_items_sold'] = $details->items_count ?? 0;
        $stats['total_revenue'] = round($details->revenue ?? 0, 2);

        return response()->json($stats);
    }

    /**
     * Get orders by status
     */
    public function byStatus($status)
    {
        $user = Auth::user();

        $orders = Order::where('status', $status)
            ->whereHas('orderItems', function ($q) use ($user) {
                $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                    $subQ->where('vendeur_id', $user->id);
                });
            })
            ->with(['user' => function ($q) {
                $q->select('id', 'name', 'email', 'phone');
            }, 'orderItems' => function ($q) use ($user) {
                $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                    $subQ->where('vendeur_id', $user->id);
                })->with('vintageProduct');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($orders);
    }

    /**
     * Export orders
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        $query = Order::whereHas('orderItems', function ($q) use ($user) {
            $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                $subQ->where('vendeur_id', $user->id);
            });
        })->with(['user', 'orderItems']);

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $csv = "ID Commande,Date,Client,Email,Statut,Total,Produits\n";
        foreach ($orders as $order) {
            $productCount = $order->orderItems->count();
            $csv .= "{$order->id},{$order->created_at},{$order->user->name},{$order->user->email},{$order->status}," . number_format($order->total_price, 2) . ",{$productCount}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="commandes.csv"');
    }
}
