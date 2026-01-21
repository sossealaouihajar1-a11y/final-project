<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'user_id',
        'payment_method',
        'transaction_id',
        'amount',
        'status',
        'paid_at',
        'gateway_response',
        'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSucceeded($query)
    {
        return $query->where('status', 'succeeded');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Methods
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSucceeded(): bool
    {
        return $this->status === 'succeeded';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function markAsSucceeded(string $transactionId, ?string $gatewayResponse = null): void
    {
        $this->update([
            'status' => 'succeeded',
            'transaction_id' => $transactionId,
            'paid_at' => now(),
            'gateway_response' => $gatewayResponse,
            'failure_reason' => null,
        ]);

        // Mettre à jour l'order et l'invoice
        $this->order->markAsPaid();
        $this->order->invoice?->markAsPaid();
    }

    public function markAsFailed(string $reason, ?string $gatewayResponse = null): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'gateway_response' => $gatewayResponse,
        ]);
    }
}