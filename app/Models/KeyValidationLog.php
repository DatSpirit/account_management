<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeyValidationLog extends Model
{
    const UPDATED_AT = null; 
    protected $fillable = [
        'product_key_id',
        'key_code',
        'ip_address',
        'user_agent',
        'device_id',
        'request_method',
        'validation_result',
        'validation_message',
        'request_data',
        'response_data',
        'validated_at',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'validated_at' => 'datetime',
    ];

    // Relationships
    public function productKey(): BelongsTo
    {
        return $this->belongsTo(ProductKey::class);
    }

    // Scopes
    public function scopeSuccess($query)
    {
        return $query->where('validation_result', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('validation_result', ['expired', 'invalid', 'suspended', 'error']);
    }

    public function scopeByIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }
}