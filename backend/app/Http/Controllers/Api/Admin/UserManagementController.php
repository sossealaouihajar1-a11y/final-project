<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Liste tous les utilisateurs avec pagination
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $role = $request->get('role');
        $search = $request->get('search');

        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }

    /**
     * Afficher un utilisateur spécifique
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|in:admin,vendeur,client',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès',
        ]);
    }

    /**
     * Suspendre un utilisateur
     */
    public function suspend(User $user): JsonResponse
    {
        $user->update(['is_active' => false]);

        return response()->json([
            'message' => 'Utilisateur suspendu avec succès',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Réactiver un utilisateur
     */
    public function reactivate(User $user): JsonResponse
    {
        $user->update(['is_active' => true]);

        return response()->json([
            'message' => 'Utilisateur réactivé avec succès',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Obtenir les statistiques des utilisateurs
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_vendors' => User::where('role', 'vendeur')->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'pending_vendors' => User::where('role', 'vendeur')->where('vendor_status', 'pending')->count(),
        ];

        return response()->json($stats);
    }
}
