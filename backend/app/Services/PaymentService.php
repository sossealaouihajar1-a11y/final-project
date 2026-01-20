<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for Stripe
     */
    public function createPaymentIntent(Order $order, $customerId = null)
    {
        try {
            $paymentIntentData = [
                'amount' => (int)($order->total_price * 100), // Convert to cents
                'currency' => 'eur',
                'description' => "Order #{$order->id}",
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                ]
            ];

            if ($customerId) {
                $paymentIntentData['customer'] = $customerId;
            }

            $paymentIntent = PaymentIntent::create($paymentIntentData);

            Log::info('Payment Intent created', [
                'intent_id' => $paymentIntent->id,
                'order_id' => $order->id,
                'amount' => $order->total_price,
            ]);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            Log::error('Error creating payment intent', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);
            throw $e;
        }
    }

    /**
     * Retrieve a payment intent
     */
    public function retrievePaymentIntent($intentId)
    {
        try {
            return PaymentIntent::retrieve($intentId);
        } catch (ApiErrorException $e) {
            Log::error('Error retrieving payment intent', [
                'error' => $e->getMessage(),
                'intent_id' => $intentId,
            ]);
            throw $e;
        }
    }

    /**
     * Record payment in database
     */
    public function recordPayment(Order $order, $paymentIntentId, $status = 'pending')
    {
        $paymentIntent = $this->retrievePaymentIntent($paymentIntentId);

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'payment_method' => 'stripe',
            'transaction_id' => $paymentIntentId,
            'amount' => $order->total_price,
            'status' => $status,
            'gateway_response' => json_encode([
                'intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'client_secret' => $paymentIntent->client_secret,
            ]),
        ]);

        Log::info('Payment recorded', [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
            'status' => $status,
        ]);

        return $payment;
    }

    /**
     * Handle successful payment (webhook or confirmation)
     */
    public function handlePaymentSuccess(Order $order, $paymentIntentId)
    {
        try {
            $payment = Payment::where('transaction_id', $paymentIntentId)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'succeeded',
                    'paid_at' => now(),
                ]);
            }

            // Update order status to paid
            $order->update([
                'status' => 'paid',
            ]);

            Log::info('Payment successful', [
                'order_id' => $order->id,
                'transaction_id' => $paymentIntentId,
            ]);

            return $order;
        } catch (\Exception $e) {
            Log::error('Error handling successful payment', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);
            throw $e;
        }
    }

    /**
     * Handle failed payment
     */
    public function handlePaymentFailure(Order $order, $paymentIntentId, $reason = null)
    {
        try {
            $payment = Payment::where('transaction_id', $paymentIntentId)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $reason,
                ]);
            }

            Log::warning('Payment failed', [
                'order_id' => $order->id,
                'transaction_id' => $paymentIntentId,
                'reason' => $reason,
            ]);
        } catch (\Exception $e) {
            Log::error('Error handling failed payment', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
            ]);
        }
    }

    /**
     * Confirm payment intent (for SCA/3D Secure)
     */
    public function confirmPaymentIntent($paymentIntentId)
    {
        try {
            $paymentIntent = $this->retrievePaymentIntent($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                return true;
            }

            return false;
        } catch (ApiErrorException $e) {
            Log::error('Error confirming payment intent', [
                'error' => $e->getMessage(),
                'intent_id' => $paymentIntentId,
            ]);
            throw $e;
        }
    }

    /**
     * Refund a payment
     */
    public function refundPayment($paymentIntentId, $amount = null)
    {
        try {
            $paymentIntent = $this->retrievePaymentIntent($paymentIntentId);

            $refundData = [
                'payment_intent' => $paymentIntentId,
            ];

            if ($amount) {
                $refundData['amount'] = (int)($amount * 100);
            }

            $refund = \Stripe\Refund::create($refundData);

            Log::info('Refund created', [
                'refund_id' => $refund->id,
                'payment_intent_id' => $paymentIntentId,
                'amount' => $amount,
            ]);

            return $refund;
        } catch (ApiErrorException $e) {
            Log::error('Error creating refund', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId,
            ]);
            throw $e;
        }
    }
}
