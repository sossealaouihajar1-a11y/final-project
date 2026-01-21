<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        // Require authentication for all payment endpoints
        $this->middleware('auth:sanctum');
    }

    /**
     * Create a payment intent
     * POST /api/payments/create-intent
     */
    public function createIntent(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|string|exists:orders,id',
            ]);

            $order = Order::findOrFail($request->order_id);

            // Verify the order belongs to the authenticated user
            if ($order->user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'Unauthorized access to this order'
                ], 403);
            }

            // Check if order is still pending payment
            if ($order->status !== 'pending') {
                return response()->json([
                    'message' => 'This order has already been processed'
                ], 400);
            }

            // Create payment intent
            $paymentIntent = $this->paymentService->createPaymentIntent($order);

            // Record payment in database
            $this->paymentService->recordPayment($order, $paymentIntent->id, 'pending');

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $order->total_price,
                'currency' => 'eur',
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating payment intent', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'message' => 'Error creating payment intent',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm a payment
     * POST /api/payments/confirm
     */
    public function confirmPayment(Request $request)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string',
                'order_id' => 'required|string|exists:orders,id',
            ]);

            $order = Order::findOrFail($request->order_id);

            // Verify the order belongs to the authenticated user
            if ($order->user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'Unauthorized access to this order'
                ], 403);
            }

            // Check payment intent status
            $paymentIntent = $this->paymentService->retrievePaymentIntent($request->payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                $this->paymentService->handlePaymentSuccess($order, $request->payment_intent_id);

                return response()->json([
                    'message' => 'Payment successful',
                    'status' => 'succeeded',
                    'order' => $order->fresh(),
                ]);
            } elseif ($paymentIntent->status === 'processing') {
                return response()->json([
                    'message' => 'Payment is processing',
                    'status' => 'processing',
                ]);
            } elseif ($paymentIntent->status === 'requires_payment_method') {
                return response()->json([
                    'message' => 'Payment method required',
                    'status' => 'requires_payment_method',
                ], 400);
            } else {
                $reason = $paymentIntent->last_payment_error?->message ?? 'Unknown error';
                $this->paymentService->handlePaymentFailure($order, $request->payment_intent_id, $reason);

                return response()->json([
                    'message' => 'Payment failed',
                    'status' => $paymentIntent->status,
                    'error' => $reason,
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error confirming payment', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'message' => 'Error confirming payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment status
     * GET /api/payments/{payment_intent_id}/status
     */
    public function getStatus(Request $request, $paymentIntentId)
    {
        try {
            $paymentIntent = $this->paymentService->retrievePaymentIntent($paymentIntentId);

            // Find the payment record
            $payment = Payment::where('transaction_id', $paymentIntentId)->first();

            if (!$payment) {
                return response()->json([
                    'message' => 'Payment not found'
                ], 404);
            }

            // Verify the payment belongs to the authenticated user
            if ($payment->user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'Unauthorized access to this payment'
                ], 403);
            }

            return response()->json([
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'currency' => $paymentIntent->currency,
                'created' => $paymentIntent->created,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting payment status', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId,
            ]);

            return response()->json([
                'message' => 'Error getting payment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Webhook handling removed — webhooks are not used in local/dev workflows
}
