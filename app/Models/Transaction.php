<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        // Basic
        'user_id',
        'product_id',
        'order_code',
        'amount',
        'status',
        'description',
        
        // Webhook security
        'is_processed',
        'processed_at',
        'webhook_signature',
        'webhook_payload',
        
        // Payment details
        'payment_reference',
        'payment_link_id',
        'account_number',
        'counter_account_name',
        'counter_account_number',
        'counter_account_bank_id',
        'counter_account_bank_name',
        'transaction_datetime',
        'currency',
        
        // Legacy
        'response_data',
    ];

    protected $casts = [
        'is_processed' => 'boolean',
        'processed_at' => 'datetime',
        'transaction_datetime' => 'datetime',
        'amount' => 'decimal:2',
        'response_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if webhook is duplicate
     */
    public function isDuplicateWebhook(string $signature): bool
    {
        return $this->webhook_signature === $signature && $this->is_processed;
    }

    /**
     * Mark transaction as processed
     */
    public function markAsProcessed(string $signature, array $payload): void
    {
        $this->update([
            'is_processed' => true,
            'processed_at' => now(),
            'webhook_signature' => $signature,
            'webhook_payload' => json_encode($payload),
        ]);
    }

    /**
     * Scopes
     */
    public function scopeProcessed($query)
    {
        return $query->where('is_processed', true);
    }

    public function scopeUnprocessed($query)
    {
        return $query->where('is_processed', false);
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}