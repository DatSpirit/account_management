<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory; 

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
        'raw_payload',
        
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
        'response_data' => 'array', // Tự động chuyển JSON DB -> Array PHP
         'raw_payload' => 'string', 
    ];

    // ==========================================
    // RELATIONS
    // ==========================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Check if webhook is duplicate
     */
    public function isDuplicateWebhook(string $signature): bool
    {
        // Kiểm tra chữ ký trùng VÀ trạng thái đã xử lý = true
        return $this->webhook_signature === $signature && $this->is_processed;
    }

    /**
     * Mark transaction as processed
     * Cập nhật trạng thái xử lý và lưu log
     */
    public function markAsProcessed(string $signature, array $payload, string $rawPayload = null): void
    {
        $updateData = [
            'is_processed' => true,
            'processed_at' => now(),
            'webhook_signature' => $signature,
            'webhook_payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ];

        // Chỉ cập nhật raw_payload nếu có dữ liệu truyền vào
        if ($rawPayload) {
            $updateData['raw_payload'] = $rawPayload;
        }

        $this->update($updateData);
    }

    // ==========================================
    // SCOPES (Query nhanh)
    // ==========================================

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