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
        $orders = Order::with(['orderItems.vintageProduct'])
            ->where('user_id', $request->user()->id)
            ->recent()
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Request $request, $id)
    {
        $order = Order::with(['orderItems.vintageProduct', 'user'])
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
            'order' => $order->fresh()
        ]);
    }
}