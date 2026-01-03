<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VintageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Créer une commande à partir du panier
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|uuid|exists:vintage_products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalPrice = 0;
            $orderItemsData = [];

            // Vérifier la disponibilité et calculer le total
            foreach ($request->items as $item) {
                $product = VintageProduct::findOrFail($item['product_id']);

                // Vérifier le stock
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'message' => "Stock insuffisant pour {$product->title}",
                        'product' => $product->title,
                        'available_stock' => $product->stock
                    ], 400);
                }

                // Vérifier que le produit est actif
                if ($product->status !== 'active') {
                    return response()->json([
                        'message' => "Le produit {$product->title} n'est plus disponible"
                    ], 400);
                }

                $price = $product->final_price;
                $subtotal = $price * $item['quantity'];
                $totalPrice += $subtotal;

                $orderItemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $price
                ];
            }

            // Créer la commande
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // Créer les items de commande et mettre à jour le stock
            foreach ($orderItemsData as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'vintage_product_id' => $itemData['product']->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price']
                ]);

                // Décrémenter le stock
                $itemData['product']->decrementStock($itemData['quantity']);
            }

            DB::commit();

            // Charger la commande avec ses relations
            $order->load(['orderItems.vintageProduct', 'user']);

            return response()->json([
                'message' => 'Commande créée avec succès',
                'order' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la création de la commande',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}