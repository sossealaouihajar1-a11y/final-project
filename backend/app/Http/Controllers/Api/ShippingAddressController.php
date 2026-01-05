<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    /**
     * Récupérer l'adresse du client
     */
    public function show(Request $request)
    {
        $address = ShippingAddress::where('user_id', $request->user()->id)
            ->latest()
            ->first();

        return response()->json($address);
    }

    /**
     * Créer ou mettre à jour l'adresse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        // Chercher l'adresse existante
        $address = ShippingAddress::where('user_id', $request->user()->id)->first();

        if ($address) {
            // Mettre à jour l'adresse existante
            $address->update($validated);
            $message = 'Adresse mise à jour avec succès';
        } else {
            // Créer une nouvelle adresse
            $address = ShippingAddress::create([
                'user_id' => $request->user()->id,
                ...$validated
            ]);
            $message = 'Adresse créée avec succès';
        }

        return response()->json([
            'message' => $message,
            'address' => $address
        ]);
    }

    /**
     * Supprimer l'adresse
     */
    public function destroy(Request $request)
    {
        ShippingAddress::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'message' => 'Adresse supprimée avec succès'
        ]);
    }
}