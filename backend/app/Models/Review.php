<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'vintage_product_id',
        'comment',
    ];

    protected $casts = [
        // No casts needed
    ];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vintageProduct()
    {
        return $this->belongsTo(VintageProduct::class);
    }

    /**
     * Scopes
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Static Methods
     */
    public static function totalReviewsForProduct($productId)
    {
        return static::where('vintage_product_id', $productId)->count();
    }
}