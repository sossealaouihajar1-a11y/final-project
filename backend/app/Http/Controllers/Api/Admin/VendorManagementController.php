<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorManagementController extends Controller
{
    /**
     * Liste des vendeurs en attente
     */
    public function pending(): JsonResponse
    {
        $vendors = User::pendingVendors()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($vendors);
    }

    /**
     * Tous les vendeurs
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::vendors();

        if ($request->has('status')) {
            $query->where('vendor_status', $request->status);
        }

        $vendors = $query->orderBy('created_at', 'desc')->get();

        return response()->json($vendors);
    }

    /**
     * Approuver un vendeur
     */
    public function approve(User $user): JsonResponse
    {
        if (!$user->isVendor()) {
            return response()->json([
                'message' => 'Cet utilisateur n\'est pas un vendeur',
            ], 400);
        }

        $user->approveVendor();

        // TODO: Envoyer un email de notification

        return response()->json([
            'message' => 'Vendeur approuvé avec succès',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Rejeter un vendeur
     */
    public function reject(User $user): JsonResponse
    {
        if (!$user->isVendor()) {
            return response()->json([
                'message' => 'Cet utilisateur n\'est pas un vendeur',
            ], 400);
        }

        $user->rejectVendor();

        // TODO: Envoyer un email de notification

        return response()->json([
            'message' => 'Vendeur rejeté',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Suspendre un vendeur
     */
    public function suspend(User $user): JsonResponse
    {
        if (!$user->isVendor()) {
            return response()->json([
                'message' => 'Cet utilisateur n\'est pas un vendeur',
            ], 400);
        }

        $user->suspendVendor();

        return response()->json([
            'message' => 'Vendeur suspendu',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Réactiver un vendeur
     */
    public function reactivate(User $user): JsonResponse
    {
        if (!$user->isVendor()) {
            return response()->json([
                'message' => 'Cet utilisateur n\'est pas un vendeur',
            ], 400);
        }

        $user->reactivateVendor();

        return response()->json([
            'message' => 'Vendeur réactivé',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Voir les détails d'un vendeur
     */
    public function show(User $user): JsonResponse
    {
        if (!$user->isVendor()) {
            return response()->json([
                'message' => 'Cet utilisateur n\'est pas un vendeur',
            ], 400);
        }

        return response()->json([
            'user' => $user->load('vintageProducts'),
        ]);
    }
}