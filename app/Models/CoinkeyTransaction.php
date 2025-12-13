<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinkeyTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Relationships
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(CoinkeyWallet::class, 'wallet_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relationship
    public function reference()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'withdraw');
    }

    public function scopePurchases($query)
    {
        return $query->where('type', 'purchase');
    }

    public function scopeRefunds($query)
    {
        return $query->where('type', 'refund');
    }
}