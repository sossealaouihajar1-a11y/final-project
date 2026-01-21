<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'vintage_product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Relations
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vintageProduct()
    {
        return $this->belongsTo(VintageProduct::class);
    }

    /**
     * Accessors
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }
}