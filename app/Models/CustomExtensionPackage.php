<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomExtensionPackage extends Model
{
    protected $fillable = [
        'name',
        'days',
        'duration_minutes',
        'price_vnd',
        'price_coinkey',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'days' => 'integer',
        'duration_minutes' => 'integer',
        'price_vnd' => 'decimal:2',
        'price_coinkey' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('days');
    }

    /**
     * Get formatted VND price
     */
    public function getFormattedPriceVndAttribute(): string
    {
        return number_format($this->price_vnd, 0, ',', '.') . ' VND';
    }

    /**
     * Get formatted Coinkey price
     */
    public function getFormattedPriceCoinkeyAttribute(): string
    {
        return number_format($this->price_coinkey, 0, ',', '.') . ' Coin';
    }
}