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
        // Webhook doesn't need authentication, so only apply middleware to specific methods
        $this->middleware('auth:sanctum')->except(['handleWebhook']);
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

    /**
     * Handle Stripe webhook
     * POST /api/payments/webhook
     */
    public function handleWebhook(Request $request)
    {
        $endpoint_secret = config('services.stripe.webhook_secret');

        $sig_header = $request->header('Stripe-Signature');
        $body = $request->getContent();

        try {
            $event = \Stripe\Webhook::constructEvent(
                $body, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentFailed($paymentIntent);
                break;

            case 'charge.refunded':
                $charge = $event->data->object;
                $this->handleChargeRefunded($charge);
                break;

            default:
                Log::info('Received unknown event type', ['type' => $event->type]);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Handle payment intent succeeded webhook event
     */
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        try {
            $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'succeeded',
                    'paid_at' => now(),
                ]);

                $order = Order::find($payment->order_id);
                if ($order) {
                    $order->update(['status' => 'paid']);

                    // Update invoice status
                    $invoice = $order->invoice;
                    if ($invoice) {
                        $invoice->update(['status' => 'paid']);
                    }

                    // Send confirmation email
                    try {
                        $pdfPath = storage_path('app/invoices/invoice-' . ($invoice->invoice_number ?? 'INV') . '.pdf');
                        if (file_exists($pdfPath)) {
                            \Illuminate\Support\Facades\Mail::to($order->user->email)
                                ->send(new \App\Mail\OrderConfirmationMail($order, $pdfPath));
                        }
                    } catch (\Exception $mailError) {
                        Log::warning('Failed to send confirmation email', [
                            'order_id' => $order->id,
                            'error' => $mailError->getMessage(),
                        ]);
                    }

                    Log::info('Payment succeeded via webhook', [
                        'order_id' => $order->id,
                        'payment_intent_id' => $paymentIntent->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error handling payment_intent.succeeded webhook', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntent->id,
            ]);
        }
    }

    /**
     * Handle payment intent failed webhook event
     */
    private function handlePaymentIntentFailed($paymentIntent)
    {
        try {
            $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

            if ($payment) {
                $reason = $paymentIntent->last_payment_error?->message ?? 'Unknown error';

                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $reason,
                ]);

                Log::warning('Payment failed via webhook', [
                    'order_id' => $payment->order_id,
                    'payment_intent_id' => $paymentIntent->id,
                    'reason' => $reason,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling payment_intent.payment_failed webhook', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntent->id,
            ]);
        }
    }

    /**
     * Handle charge refunded webhook event
     */
    private function handleChargeRefunded($charge)
    {
        try {
            $payment = Payment::where('gateway_response', 'like', '%' . $charge->payment_intent . '%')->first();

            if ($payment) {
                $payment->update([
                    'status' => 'refunded',
                ]);

                Log::info('Charge refunded via webhook', [
                    'order_id' => $payment->order_id,
                    'charge_id' => $charge->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling charge.refunded webhook', [
                'error' => $e->getMessage(),
                'charge_id' => $charge->id,
            ]);
        }
    }
}
