<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VintageProduct;
use App\Models\Invoice;
use App\Models\ShippingAddress;
use App\Models\Payment;
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
    // Log incoming data
    Log::info('Checkout request:', [
        'items' => $request->items,
        'product_ids' => collect($request->items)->pluck('product_id')->toArray()
    ]);

    // Validations de base
    $request->validate([
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|string',
        'items.*.quantity' => 'required|integer|min:1',
        'payment_method' => 'required|in:stripe,cash_on_delivery',
    ]);

    // Vérifier que les products_id existent
    $productIds = collect($request->items)->pluck('product_id')->toArray();
    $existingProducts = VintageProduct::whereIn('id', $productIds)->pluck('id')->toArray();
    
    Log::info('Product check:', [
        'requested_ids' => $productIds,
        'existing_ids' => $existingProducts,
        'total_products_in_db' => VintageProduct::count()
    ]);
    
    foreach ($productIds as $productId) {
        if (!in_array($productId, $existingProducts)) {
            return response()->json([
                'message' => 'Un ou plusieurs produits n\'existent pas',
                'product_id' => $productId
            ], 422);
        }
    }

    $user = $request->user();

    // Vérifier l'adresse
    $shippingAddress = $user->shippingAddress;
    if (!$shippingAddress) {
        return response()->json([
            'message' => 'Veuillez enregistrer une adresse de livraison avant de passer commande'
        ], 400);
    }

    DB::beginTransaction();

    try {
        // Calculer le total
        $subtotal = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = VintageProduct::findOrFail($item['product_id']);

            if ($product->stock < $item['quantity']) {
                DB::rollBack();
                return response()->json([
                    'message' => "Stock insuffisant pour {$product->title}"
                ], 400);
            }

            $price = $product->promotion > 0 
                ? $product->price * (1 - $product->promotion / 100)
                : $product->price;

            $itemTotal = $price * $item['quantity'];
            $subtotal += $itemTotal;

            $orderItems[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'price' => $price,
                'total' => $itemTotal,
            ];
        }

        // Frais de livraison
        $shippingCost = $subtotal >= 400 ? 0.00 : 5.00;
        $totalPrice = $subtotal + $shippingCost;

        // Créer la commande avec payment_method
        $order = Order::create([
            'user_id' => $user->id,
            'shipping_address_id' => $shippingAddress->id,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_method' => $request->payment_method, // NOUVEAU
        ]);

        // Créer les items
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'vintage_product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            $item['product']->decrement('stock', $item['quantity']);
        }

        // Créer la facture
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 6, '0', STR_PAD_LEFT);

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totalPrice,
            'status' => 'unpaid',
            'payment_method' => $request->payment_method, // NOUVEAU
        ]);

        // Générer le PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', [
            'order' => $order->load(['orderItems.vintageProduct', 'shippingAddress', 'user']),
            'invoice' => $invoice,
        ]);

        if (!file_exists(storage_path('app/invoices'))) {
            mkdir(storage_path('app/invoices'), 0755, true);
        }

        $pdfPath = storage_path('app/invoices/invoice-' . $invoiceNumber . '.pdf');
        $pdf->save($pdfPath);

        // Create Payment record
        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'payment_method' => $request->payment_method,
            'amount' => $totalPrice,
            'status' => $request->payment_method === 'stripe' ? 'pending' : 'pending',
        ]);

        // Send email only for cash_on_delivery
        // For stripe, email is sent after successful payment
        if ($request->payment_method === 'cash_on_delivery') {
            Mail::to($user->email)->send(new \App\Mail\OrderConfirmationMail($order, $pdfPath));
        }

        DB::commit();

        return response()->json([
            'message' => 'Commande créée avec succès',
            'order' => $order->load(['orderItems.vintageProduct', 'invoice']),
            'payment' => $request->payment_method === 'stripe' ? $payment : null,
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