<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductKey extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'transaction_id',
        'key_code',
        'key_type',
        'duration_minutes',
        'key_cost',
        'status',
        'activated_at',
        'expires_at',
        'last_validated_at',
        'validation_count',
        'assigned_to_email',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'key_cost' => 'decimal:2',
        'duration_minutes' => 'integer',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_validated_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function validationLogs(): HasMany
    {
        return $this->hasMany(KeyValidationLog::class);
    }

    // Static Methods
    public static function generateKey(int $length = 20): string
    {
        return strtoupper(Str::random($length));
    }

    public static function generateFormattedKey(string $prefix = 'PK', int $segments = 4, int $segmentLength = 4): string
    {
        $parts = [$prefix];
        for ($i = 0; $i < $segments; $i++) {
            $parts[] = strtoupper(Str::random($segmentLength));
        }
        return implode('-', $parts);
    }

    // Instance Methods
    public function isActive(): bool
    {
        return $this->status === 'active' &&
            ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' ||
            ($this->expires_at && $this->expires_at->isPast());
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function activate(): void
    {
        if (!$this->activated_at) {
            $this->activated_at = now();

            if ($this->duration_minutes > 0) {
                $this->expires_at = now()->addMinutes($this->duration_minutes);
            }
        }

        $this->status = 'active';
        $this->save();
    }

    public function suspend(string $reason = null): void
    {
        $this->status = 'suspended';
        if ($reason) {
            $this->notes = ($this->notes ?? '') . "\nSuspended: " . $reason;
        }
        $this->save();
    }

    public function revoke(string $reason = null): void
    {
        $this->status = 'revoked';
        if ($reason) {
            $this->notes = ($this->notes ?? '') . "\nRevoked: " . $reason;
        }
        $this->save();
    }

    public function extend(int $additionalMinutes): void
    {
        // Nếu chưa có ngày hết hạn HOẶC đã hết hạn trong quá khứ -> Tính từ NOW
        if (!$this->expires_at || $this->expires_at->isPast()) {
            $this->expires_at = now()->addMinutes($additionalMinutes);
        } else {
            // Nếu còn hạn -> Cộng dồn vào thời gian hiện tại
            $this->expires_at = $this->expires_at->addMinutes($additionalMinutes);
        }

        $this->duration_minutes += $additionalMinutes;
        $this->status = 'active'; // Luôn kích hoạt lại khi gia hạn
        $this->save();

        // Ghi log lịch sử 
        \App\Models\KeyHistory::log($this->id, 'extend', "Gia hạn thêm {$additionalMinutes} phút");
    }

    public function getRemainingSeconds(): ?int
    {
        if (!$this->expires_at) {
            return null; // Không giới hạn
        }

        $remaining = now()->diffInSeconds($this->expires_at, false);
        return $remaining > 0 ? $remaining : 0;
    }

    public function getRemainingMinutes(): ?int
    {
        $seconds = $this->getRemainingSeconds();

        if ($seconds === null) {
            return null;
        }

        return (int) floor($seconds / 60);
    }


    public function getRemainingDays(): ?float
    {
        $minutes = $this->getRemainingMinutes();
        
        return $minutes !== null ? round($minutes / 1440, 1) : null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere(function ($q) {
                $q->where('expires_at', '<=', now())
                    ->whereNotNull('expires_at');
            });
    }

    public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->where('status', 'active')
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }
}
