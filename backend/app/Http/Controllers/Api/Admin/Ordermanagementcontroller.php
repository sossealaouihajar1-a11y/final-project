<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Ordermanagementcontroller extends Controller
{
    /**
     * Récupérer toutes les commandes avec filtres
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.vintageProduct.vendeur']);

        // Filtre par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filtre par date
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('orders.id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = $request->get('per_page', 15);
        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Formater les données
        $orders->getCollection()->transform(function ($order) {
            // Get vendor from first product in order
            $vendor = null;
            if ($order->orderItems->count() > 0) {
                $vendor = $order->orderItems->first()->vintageProduct->vendeur;
            }

            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'client_name' => $order->user->name ?? 'N/A',
                'client_email' => $order->user->email ?? 'N/A',
                'client_phone' => $order->user->phone ?? null,
                'vendor_name' => $vendor->name ?? 'N/A',
                'vendor_email' => $vendor->email ?? 'N/A',
                'vendor_phone' => $vendor->phone ?? null,
                'items_count' => $order->orderItems->sum('quantity'),
                'total_price' => $order->total_price,
                'subtotal' => $order->subtotal,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ];
        });

        return response()->json($orders);
    }

    /**
     * Récupérer les statistiques des commandes
     */
    public function statistics()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'paid')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'canceled')->count(),
            'total_revenue' => Order::whereIn('status', ['delivered', 'shipped', 'paid'])->sum('total_price'),
            'revenue_this_month' => Order::whereIn('status', ['delivered', 'shipped', 'paid'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_price'),
            'orders_this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Récupérer les détails d'une commande
     */
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.vintageProduct.vendeur'])
            ->findOrFail($id);

        // Get vendor from first product
        $vendor = null;
        if ($order->orderItems->count() > 0) {
            $vendor = $order->orderItems->first()->vintageProduct->vendeur;
        }

        return response()->json([
            'id' => $order->id,
            'order_number' => $order->order_number,
            'client_name' => $order->user->name ?? 'N/A',
            'client_email' => $order->user->email ?? 'N/A',
            'client_phone' => $order->user->phone ?? null,
            'vendor_name' => $vendor->name ?? 'N/A',
            'vendor_email' => $vendor->email ?? 'N/A',
            'vendor_phone' => $vendor->phone ?? null,
            'items' => $order->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->vintageProduct->title ?? 'Produit supprimé',
                    'product_image' => $item->vintageProduct->image_url ?? null,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
            'subtotal' => $order->subtotal,
            'shipping_cost' => 0,
            'tax' => 0,
            'total_price' => $order->total_price,
            'status' => $order->status,
            'shipping_address' => $order->user->address ?? null,
            'notes' => null,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
        ]);
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,delivered,canceled'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Statut mis à jour avec succès',
            'order' => $order
        ]);
    }

    /**
     * Supprimer une commande (soft delete)
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // Soft delete des items
        $order->items()->delete();
        
        // Soft delete de la commande
        $order->delete();

        return response()->json([
            'message' => 'Commande supprimée avec succès'
        ]);
    }

    /**
     * Exporter les commandes en CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'orderItems.vintageProduct.vendeur']);

        // Appliquer les mêmes filtres que l'index
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Créer le CSV avec UTF-8 BOM pour Excel
        $csvData = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csvData .= "N° Commande,Client,Email Client,Vendeur,Email Vendeur,Prix Total,Statut,Date\n";
        
        foreach ($orders as $order) {
            // Get vendor from first product
            $vendor = null;
            if ($order->orderItems->count() > 0) {
                $vendor = $order->orderItems->first()->vintageProduct->vendeur;
            }

            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $order->order_number,
                $order->user->name ?? 'N/A',
                $order->user->email ?? 'N/A',
                $vendor->name ?? 'N/A',
                $vendor->email ?? 'N/A',
                number_format($order->total_price, 2, ',', ' '),
                $this->getStatusLabelFr($order->status),
                $order->created_at->format('d/m/Y H:i:s')
            );
        }

        $fileName = 'commandes_' . date('Y-m-d_His') . '.csv';

        return response($csvData, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }

    /**
     * Obtenir le label en français pour un statut
     */
    private function getStatusLabelFr($status)
    {
        $labels = [
            'pending' => 'En attente',
            'paid' => 'Payée',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'canceled' => 'Annulée',
        ];

        return $labels[$status] ?? $status;
    }
}