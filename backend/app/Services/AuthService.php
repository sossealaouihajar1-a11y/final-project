<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Inscription d'un client
     */
    public function registerClient(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'client',
            'phone' => $data['phone'] ?? null,
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Inscription d'un vendeur
     */
    public function registerVendor(array $data): User
    {
        // Upload du document d'identité
        $identityPath = null;
        if (isset($data['identity_document'])) {
            $identityPath = $data['identity_document']->store('identity_documents', 'public');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'vendeur',
            'phone' => $data['phone'],
            'city' => $data['city'],
            'address' => $data['address'],
            'identity_type' => $data['identity_type'],
            'identity_document' => $identityPath,
            'vendor_status' => 'pending',
        ]);

        return $user;
    }

    /**
     * Connexion
     */
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        // Vérifications selon le rôle
        if ($user->isVendor()) {
            if ($user->vendor_status === 'pending') {
                throw ValidationException::withMessages([
                    'email' => ['Votre compte vendeur est en attente de validation par l\'administrateur.'],
                ]);
            }

            if ($user->vendor_status === 'rejected') {
                throw ValidationException::withMessages([
                    'email' => ['Votre compte vendeur a été rejeté.'],
                ]);
            }

            if ($user->vendor_status === 'suspended') {
                throw ValidationException::withMessages([
                    'email' => ['Votre compte vendeur a été suspendu.'],
                ]);
            }
        }

        // Vérifier si le compte est supprimé (soft delete)
        if ($user->trashed()) {
            throw ValidationException::withMessages([
                'email' => ['Ce compte a été désactivé.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Déconnexion
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Déconnexion de tous les appareils
     */
    public function logoutAll(User $user): void
    {
        $user->tokens()->delete();
    }
}