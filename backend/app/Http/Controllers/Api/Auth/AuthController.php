<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterClientRequest;
use App\Http\Requests\Auth\RegisterVendorRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Inscription Client
     */
    public function registerClient(RegisterClientRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->registerClient($request->validated());

            return response()->json([
                'message' => 'Inscription réussie',
                'user' => $result['user'],
                'token' => $result['token'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'inscription',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Inscription Vendeur
     */
    public function registerVendor(RegisterVendorRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerVendor($request->validated());

            return response()->json([
                'message' => 'Votre demande de compte vendeur a été soumise. Vous recevrez un email une fois validé par l\'administrateur.',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'inscription',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Connexion
     */
 public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return response()->json([
            'message' => 'Erreur de connexion',
            'errors' => [
                'email' => ['Les identifiants fournis sont incorrects.']
            ]
        ], 422);
    }

    // Vérification vendeur
    if ($user->role === 'vendeur') {
        if ($user->vendor_status === 'pending') {
            Auth::logout();
            return response()->json([
                'message' => 'Erreur de connexion',
                'errors' => [
                    'email' => ['Votre compte vendeur est en attente de validation.']
                ]
            ], 422);
        }

        if ($user->vendor_status === 'rejected') {
            Auth::logout();
            return response()->json([
                'message' => 'Erreur de connexion',
                'errors' => [
                    'email' => ['Votre compte vendeur a été rejeté.']
                ]
            ], 422);
        }

        if ($user->vendor_status === 'suspended') {
            Auth::logout();
            return response()->json([
                'message' => 'Erreur de connexion',
                'errors' => [
                    'email' => ['Votre compte vendeur a été suspendu.']
                ]
            ], 422);
        }
    }

    // Supprimer les anciens tokens
    $user->tokens()->delete();

    // Créer un nouveau token
    $token = $user->createToken('auth-token')->plainTextToken;

    return response()->json([
        'message' => 'Connexion réussie',
        'user' => $user,
        'token' => $token
    ], 200);
}



    /**
     * Déconnexion
     */
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Déconnexion réussie'
    ], 200);
}

    /**
     * Utilisateur connecté
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}