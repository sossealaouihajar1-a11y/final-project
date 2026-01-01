<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VintageProduct extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'vendeur_id',
        'title',
        'description',
        'category',
        'price',
        'promotion',
        'condition',
        'stock',
        'status',
        'image_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'promotion' => 'decimal:2',
        'stock' => 'integer',
    ];
    protected $appends = ['final_price'];

    /**
     * Relations
     */
    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0)
                     ->where('status', '!=', 'sold_out');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeWithPromotion($query)
    {
        return $query->where('promotion', '>', 0);
    }

    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Accessors & Mutators
     */
    public function getFinalPriceAttribute()
    {
        if ($this->promotion > 0) {
            return $this->price - ($this->price * $this->promotion / 100);
        }
        return $this->price;
    }

    public function getImageUrlAttribute($value)
    {
        if ($value) {
            // Si c'est déjà une URL complète, retourner tel quel
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            // Sinon, construire l'URL avec storage
            return asset('storage/' . $value);
        }
        // Image par défaut si aucune image
        return asset('images/default-product.jpg');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->stock > 0;
    }

    public function isSoldOut(): bool
    {
        return $this->stock === 0 || $this->status === 'sold_out';
    }

    /**
     * Methods
     */
    public function decrementStock(int $quantity): bool
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            
            if ($this->stock === 0) {
                $this->update(['status' => 'sold_out']);
            }
            
            return true;
        }
        
        return false;
    }

    public function incrementStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
        
        if ($this->status === 'sold_out' && $this->stock > 0) {
            $this->update(['status' => 'active']);
        }
    }
}