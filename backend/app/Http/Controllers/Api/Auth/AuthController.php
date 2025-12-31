<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterClientRequest;
use App\Http\Requests\Auth\RegisterVendorRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());

            return response()->json([
                'message' => 'Connexion réussie',
                'user' => $result['user'],
                'token' => $result['token'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de connexion',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
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