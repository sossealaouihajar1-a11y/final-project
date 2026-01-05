<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Afficher toutes les commandes de l'utilisateur
     */
    public function index(Request $request)
    {
        $orders = Order::with(['orderItems.vintageProduct', 'shippingAddress'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Request $request, $id)
    {
        $order = Order::with(['orderItems.vintageProduct', 'user', 'shippingAddress', 'invoice'])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json($order);
    }

    /**
     * Annuler une commande
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if (!$order->canBeCanceled()) {
            return response()->json([
                'message' => 'Cette commande ne peut plus être annulée'
            ], 400);
        }

        $order->cancel();

        return response()->json([
            'message' => 'Commande annulée avec succès',
            'order' => $order->fresh(['orderItems.vintageProduct'])
        ]);
    }

    /**
     * Statistiques des commandes
     */
    public function stats(Request $request)
    {
        $userId = $request->user()->id;

        $stats = [
            'total_orders' => Order::where('user_id', $userId)->count(),
            'pending_orders' => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'delivered_orders' => Order::where('user_id', $userId)->where('status', 'delivered')->count(),
            'canceled_orders' => Order::where('user_id', $userId)->where('status', 'canceled')->count(),
            'total_spent' => Order::where('user_id', $userId)
                ->whereIn('status', ['pending', 'paid', 'shipped', 'delivered'])
                ->sum('total_price'),
        ];

        return response()->json($stats);
    }
}