<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Get all clients who have purchased from this vendor
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get unique clients who have purchased from this vendor
        $query = User::query()
            ->where('role', 'client')
            ->whereHas('orders', function ($q) use ($user) {
                $q->whereHas('orderItems', function ($subQ) use ($user) {
                    $subQ->whereHas('vintageProduct', function ($prodQ) use ($user) {
                        $prodQ->where('vendeur_id', $user->id);
                    });
                });
            });

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $clients = $query->select('id', 'name', 'email', 'phone', 'city', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));

        return response()->json($clients);
    }

    /**
     * Get client details and purchase history
     */
    public function show($clientId)
    {
        $user = Auth::user();
        $client = User::findOrFail($clientId);

        // Get all orders from this client that include products from this vendor
        $orders = Order::where('user_id', $clientId)
            ->whereHas('orderItems', function ($q) use ($user) {
                $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                    $subQ->where('vendeur_id', $user->id);
                });
            })
            ->with(['orderItems' => function ($q) use ($user) {
                $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                    $subQ->where('vendeur_id', $user->id);
                })->with('vintageProduct');
            }, 'shippingAddress'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_purchases' => $orders->count(),
            'total_spent' => $orders->sum(function ($order) {
                return $order->orderItems->sum('total_price');
            }),
            'last_purchase' => $orders->first()?->created_at,
            'average_order_value' => $orders->count() > 0 
                ? round($orders->sum(function ($order) { return $order->orderItems->sum('total_price'); }) / $orders->count(), 2)
                : 0,
        ];

        return response()->json([
            'client' => $client,
            'orders' => $orders,
            'statistics' => $stats,
        ]);
    }

    /**
     * Get client purchase statistics
     */
    public function statistics()
    {
        $user = Auth::user();

        $stats = [
            'total_clients' => User::where('role', 'client')
                ->whereHas('orders', function ($q) use ($user) {
                    $q->whereHas('orderItems', function ($subQ) use ($user) {
                        $subQ->whereHas('vintageProduct', function ($prodQ) use ($user) {
                            $prodQ->where('vendeur_id', $user->id);
                        });
                    });
                })->count(),
            'repeat_clients' => 0, // Will be calculated below
            'total_orders' => Order::whereHas('orderItems', function ($q) use ($user) {
                $q->whereHas('vintageProduct', function ($subQ) use ($user) {
                    $subQ->where('vendeur_id', $user->id);
                });
            })->count(),
            'total_revenue' => 0, // Will be calculated below
        ];

        // Calculate revenue
        $revenue = \DB::table('order_items')
            ->join('vintage_products', 'order_items.vintage_product_id', '=', 'vintage_products.id')
            ->where('vintage_products.vendeur_id', $user->id)
            ->selectRaw('SUM(order_items.price * order_items.quantity) as total')
            ->value('total') ?? 0;
        $stats['total_revenue'] = round($revenue, 2);

        // Count repeat clients
        $repeatClients = \DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('vintage_products', 'order_items.vintage_product_id', '=', 'vintage_products.id')
            ->where('vintage_products.vendeur_id', $user->id)
            ->select('orders.user_id')
            ->groupBy('orders.user_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();
        $stats['repeat_clients'] = $repeatClients;

        return response()->json($stats);
    }

    /**
     * Get recent orders from clients
     */
    public function recentOrders(Request $request)
    {
        $user = Auth::user();
        $limit = $request->input('limit', 10);

        $orders = Order::whereHas('orderItems', function ($q) use ($user) {
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
        ->limit($limit)
        ->get();

        return response()->json($orders);
    }

    /**
     * Export client list
     */
    public function exportClients()
    {
        $user = Auth::user();

        $clients = User::where('role', 'client')
            ->whereHas('orders', function ($q) use ($user) {
                $q->whereHas('orderItems', function ($subQ) use ($user) {
                    $subQ->whereHas('vintageProduct', function ($prodQ) use ($user) {
                        $prodQ->where('vendeur_id', $user->id);
                    });
                });
            })
            ->select('id', 'name', 'email', 'phone', 'city', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $csv = "ID,Nom,Email,Téléphone,Ville,Date d'inscription\n";
        foreach ($clients as $client) {
            $csv .= "{$client->id},{$client->name},{$client->email},{$client->phone},{$client->city},{$client->created_at}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="clients.csv"');
    }
}
