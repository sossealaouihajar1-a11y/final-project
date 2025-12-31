<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isVendor()) {
            return response()->json([
                'message' => 'Accès réservé aux vendeurs',
            ], 403);
        }

        if (!$user->isApprovedVendor()) {
            return response()->json([
                'message' => 'Votre compte vendeur n\'est pas approuvé',
            ], 403);
        }

        return $next($request);
    }
}