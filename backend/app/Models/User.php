<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'city',
        'address',
        'avatar',
        'vendor_status',
        'identity_type',
        'identity_document',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'deleted_at' => 'datetime',
    ];

    // Relations
    public function vintageProducts()
    {
        return $this->hasMany(VintageProduct::class, 'vendeur_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shippingAddresses()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeVendors($query)
    {
        return $query->where('role', 'vendeur');
    }

    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeApprovedVendors($query)
    {
        return $query->where('role', 'vendeur')
                     ->where('vendor_status', 'approved');
    }

    public function scopePendingVendors($query)
    {
        return $query->where('role', 'vendeur')
                     ->where('vendor_status', 'pending');
    }

    public function scopeRejectedVendors($query)
    {
        return $query->where('role', 'vendeur')
                     ->where('vendor_status', 'rejected');
    }

    public function scopeSuspendedVendors($query)
    {
        return $query->where('role', 'vendeur')
                     ->where('vendor_status', 'suspended');
    }

    // 
     public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeVendorStatus($query, $status)
    {
        return $query->where('vendor_status', $status);
    }
    // Helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendeur';
    }

    public function isApprovedVendor(): bool
    {
        return $this->isVendor() && $this->vendor_status === 'approved';
    }

    public function canSellProducts(): bool
    {
        return $this->isApprovedVendor();
    }

    // Vendor actions
    public function approveVendor(): void
    {
        if ($this->isVendor()) {
            $this->update(['vendor_status' => 'approved']);
        }
    }

    public function rejectVendor(): void
    {
        if ($this->isVendor()) {
            $this->update(['vendor_status' => 'rejected']);
        }
    }

    public function suspendVendor(): void
    {
        if ($this->isVendor()) {
            $this->update(['vendor_status' => 'suspended']);
            $this->vintageProducts()->update(['status' => 'inactive']);
        }
    }

    public function reactivateVendor(): void
    {
        if ($this->isVendor() && $this->vendor_status === 'suspended') {
            $this->update(['vendor_status' => 'approved']);
        }
    }

    // Accessors
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }

    public function getIdentityDocumentUrlAttribute(): ?string
    {
        return $this->identity_document
            ? asset('storage/' . $this->identity_document)
            : null;
    }
}