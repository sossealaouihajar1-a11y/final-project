<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VintageProduct;
use App\Models\Invoice;
use App\Models\ShippingAddress;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

    $user = $request->user();

    // Vérifier que l'utilisateur est un client
    if ($user->role !== 'client') {
        return response()->json([
            'message' => 'Seuls les clients peuvent passer des commandes'
        ], 403);
    }

    // Vérifier que le client a une adresse de livraison
    $shippingAddress = ShippingAddress::where('user_id', $user->id)->first();
    
    if (!$shippingAddress) {
        return response()->json([
            'message' => 'Veuillez enregistrer une adresse de livraison avant de passer commande'
        ], 400);
    }

    try {
        DB::beginTransaction();

        $totalPrice = 0;
        $orderItemsData = [];

        // Vérifier la disponibilité et calculer le total
        foreach ($request->items as $item) {
            $product = VintageProduct::findOrFail($item['product_id']);

            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => "Stock insuffisant pour {$product->title}",
                    'product' => $product->title,
                    'available_stock' => $product->stock
                ], 400);
            }

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

        // Créer la commande avec l'adresse existante
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'shipping_address_id' => $shippingAddress->id,
        ]);

        Log::info('Order created', ['id' => $order->id]);

        // Créer les items de commande
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

        // Créer la facture
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'subtotal' => $totalPrice,
            'shipping_cost' => 0.00,
            'total_amount' => $totalPrice,
            'status' => 'unpaid',
        ]);

        DB::commit();

        // Charger les relations pour l'email
        $order->load([
            'orderItems.vintageProduct',
            'user',
            'shippingAddress',
            'invoice'
        ]);

        // Envoyer l'email avec la facture
        try {
            Mail::to($user->email)->send(new OrderConfirmationMail($order));
            Log::info('Confirmation email sent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Commande créée avec succès',
            'order' => $order
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Checkout error: ' . $e->getMessage());
        
        return response()->json([
            'message' => 'Erreur lors de la création de la commande',
            'error' => $e->getMessage()
        ], 500);
    }
}
}