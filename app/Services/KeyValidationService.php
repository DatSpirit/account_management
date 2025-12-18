<?php

namespace App\Services;

use App\Models\ProductKey;
use App\Models\KeyValidationLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class KeyValidationService
{
    // ==========================================
    // CONFIGURATION
    // ==========================================
    
    const CACHE_LOCK_TTL = 5; // seconds - Cache lock duration
    const MAX_VALIDATIONS_PER_MINUTE = 10; // Rate limit per IP
    const MAX_VALIDATIONS_PER_KEY_PER_HOUR = 60; // Per key limit
    const IDEMPOTENCY_WINDOW = 300; // 5 minutes for idempotency
    
    // ==========================================
    // LAYER 1: CACHE ATOMIC LOCKING
    // ==========================================
    
    /**
     * Acquire distributed lock using Cache atomic operations
     * Prevents race conditions across multiple servers
     */
    private function acquireLock(string $lockKey, int $ttl = self::CACHE_LOCK_TTL): bool
    {
        $lockValue = Str::uuid()->toString();
        
        // Try to acquire lock (atomic operation)
        $acquired = Cache::add($lockKey, $lockValue, $ttl);
        
        if ($acquired) {
            Log::debug("Lock acquired", ['key' => $lockKey, 'value' => $lockValue]);
            return true;
        }
        
        Log::warning("Lock acquisition failed", ['key' => $lockKey]);
        return false;
    }
    
    /**
     * Release distributed lock
     */
    private function releaseLock(string $lockKey): void
    {
        Cache::forget($lockKey);
        Log::debug("Lock released", ['key' => $lockKey]);
    }
    
    // ==========================================
    // LAYER 2: DATABASE PESSIMISTIC LOCKING
    // ==========================================
    
    /**
     * Get key with database row lock
     * Ensures data consistency during transaction
     */
    private function getKeyWithLock(string $keyCode): ?ProductKey
    {
        return ProductKey::where('key_code', $keyCode)
            ->lockForUpdate() // Pessimistic lock
            ->first();
    }
    
    // ==========================================
    // LAYER 3: IDEMPOTENCY MECHANISM
    // ==========================================
    
    /**
     * Generate idempotency key from request parameters
     */
    private function generateIdempotencyKey(
        string $keyCode, 
        ?string $ipAddress, 
        ?string $deviceId
    ): string {
        // Combine factors to create unique request fingerprint
        $components = [
            $keyCode,
            $ipAddress ?? 'unknown',
            $deviceId ?? 'no-device',
            floor(time() / 60) // Time bucket (1 minute)
        ];
        
        return 'idempotency:validation:' . hash('sha256', implode('|', $components));
    }
    
    /**
     * Check if request is duplicate (idempotency check)
     */
    private function isDuplicateRequest(string $idempotencyKey): bool
    {
        return Cache::has($idempotencyKey);
    }
    
    /**
     * Get cached validation result
     */
    private function getCachedResult(string $idempotencyKey): ?array
    {
        return Cache::get($idempotencyKey);
    }
    
    /**
     * Cache validation result for idempotency
     */
    private function cacheResult(string $idempotencyKey, array $result): void
    {
        Cache::put($idempotencyKey, $result, self::IDEMPOTENCY_WINDOW);
    }
    
    // ==========================================
    // LAYER 4: RATE LIMITING & ANTI-SPAM
    // ==========================================
    
    /**
     * Check IP-based rate limit
     */
    private function checkIpRateLimit(string $ipAddress): bool
    {
        $rateLimitKey = "rate_limit:ip:{$ipAddress}";
        $currentCount = (int) Cache::get($rateLimitKey, 0);
        
        if ($currentCount >= self::MAX_VALIDATIONS_PER_MINUTE) {
            Log::warning("IP rate limit exceeded", [
                'ip' => $ipAddress,
                'count' => $currentCount
            ]);
            return false;
        }
        
        // Increment counter (expires in 60 seconds)
        Cache::put($rateLimitKey, $currentCount + 1, 60);
        return true;
    }
    
    /**
     * Check per-key rate limit
     */
    private function checkKeyRateLimit(string $keyCode): bool
    {
        $rateLimitKey = "rate_limit:key:{$keyCode}";
        $currentCount = (int) Cache::get($rateLimitKey, 0);
        
        if ($currentCount >= self::MAX_VALIDATIONS_PER_KEY_PER_HOUR) {
            Log::warning("Key rate limit exceeded", [
                'key_code' => $keyCode,
                'count' => $currentCount
            ]);
            return false;
        }
        
        // Increment counter (expires in 1 hour)
        Cache::put($rateLimitKey, $currentCount + 1, 3600);
        return true;
    }
    
    /**
     * Detect suspicious validation patterns
     */
    private function detectSuspiciousPattern(
        string $keyCode,
        string $ipAddress,
        ?string $deviceId
    ): bool {
        // Check if same key validated from too many different IPs recently
        $recentIpsKey = "validation:ips:{$keyCode}";
        $recentIps = Cache::get($recentIpsKey, []);
        
        if (!in_array($ipAddress, $recentIps)) {
            $recentIps[] = $ipAddress;
            Cache::put($recentIpsKey, $recentIps, 600); // 10 minutes
        }
        
        // Suspicious if more than 5 different IPs in 10 minutes
        if (count($recentIps) > 5) {
            Log::alert("Suspicious validation pattern detected", [
                'key_code' => $keyCode,
                'ip_count' => count($recentIps),
                'ips' => $recentIps
            ]);
            return true;
        }
        
        return false;
    }
    
    // ==========================================
    // MAIN VALIDATION METHOD WITH ALL 4 LAYERS
    // ==========================================
    
    /**
     * Validate key with full protection layers
     */
    public function validateKey(
        string $keyCode,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?string $deviceId = null
    ): array {
        $startTime = microtime(true);
        $requestId = Str::uuid()->toString();
        
        Log::info("Validation request started", [
            'request_id' => $requestId,
            'key_code' => substr($keyCode, 0, 8) . '***',
            'ip' => $ipAddress
        ]);
        
        try {
            // ==========================================
            // LAYER 1: CACHE LOCK
            // ==========================================
            $lockKey = "validation_lock:{$keyCode}";
            
            if (!$this->acquireLock($lockKey)) {
                return [
                    'valid' => false,
                    'status' => 'rate_limited',
                    'message' => 'Validation in progress, please retry',
                    'request_id' => $requestId
                ];
            }
            
            // ==========================================
            // LAYER 3: IDEMPOTENCY CHECK
            // ==========================================
            $idempotencyKey = $this->generateIdempotencyKey(
                $keyCode, 
                $ipAddress, 
                $deviceId
            );
            
            if ($this->isDuplicateRequest($idempotencyKey)) {
                $cachedResult = $this->getCachedResult($idempotencyKey);
                
                if ($cachedResult) {
                    Log::info("Returning cached result (idempotency)", [
                        'request_id' => $requestId,
                        'key_code' => substr($keyCode, 0, 8) . '***'
                    ]);
                    
                    $this->releaseLock($lockKey);
                    return array_merge($cachedResult, [
                        'cached' => true,
                        'request_id' => $requestId
                    ]);
                }
            }
            
            // ==========================================
            // LAYER 4: RATE LIMITING & ANTI-SPAM
            // ==========================================
            
            // IP rate limit
            if (!$this->checkIpRateLimit($ipAddress ?? 'unknown')) {
                $this->releaseLock($lockKey);
                return [
                    'valid' => false,
                    'status' => 'rate_limited',
                    'message' => 'Too many requests from your IP',
                    'request_id' => $requestId
                ];
            }
            
            // Per-key rate limit
            if (!$this->checkKeyRateLimit($keyCode)) {
                $this->releaseLock($lockKey);
                return [
                    'valid' => false,
                    'status' => 'rate_limited',
                    'message' => 'This key is being validated too frequently',
                    'request_id' => $requestId
                ];
            }
            
            // Suspicious pattern detection
            if ($this->detectSuspiciousPattern($keyCode, $ipAddress ?? 'unknown', $deviceId)) {
                $this->logValidation(
                    null,
                    $keyCode,
                    'suspicious',
                    'Suspicious validation pattern detected',
                    $ipAddress,
                    $userAgent,
                    $deviceId
                );
                
                $this->releaseLock($lockKey);
                return [
                    'valid' => false,
                    'status' => 'suspicious',
                    'message' => 'Suspicious activity detected, key temporarily locked',
                    'request_id' => $requestId
                ];
            }
            
            // ==========================================
            // LAYER 2: DATABASE TRANSACTION WITH PESSIMISTIC LOCK
            // ==========================================
            
            DB::beginTransaction();
            
            try {
                $key = $this->getKeyWithLock($keyCode);
                
                if (!$key) {
                    $result = [
                        'valid' => false,
                        'status' => 'invalid',
                        'message' => 'Key not found',
                        'request_id' => $requestId
                    ];
                    
                    $this->logValidation(
                        null,
                        $keyCode,
                        'invalid',
                        'Key not found',
                        $ipAddress,
                        $userAgent,
                        $deviceId
                    );
                    
                    DB::commit();
                    $this->cacheResult($idempotencyKey, $result);
                    $this->releaseLock($lockKey);
                    return $result;
                }
                
                // Check suspended
                if ($key->isSuspended()) {
                    $result = [
                        'valid' => false,
                        'status' => 'suspended',
                        'message' => 'Key is suspended',
                        'request_id' => $requestId
                    ];
                    
                    $this->logValidation(
                        $key->id,
                        $keyCode,
                        'suspended',
                        'Key is suspended',
                        $ipAddress,
                        $userAgent,
                        $deviceId
                    );
                    
                    DB::commit();
                    $this->cacheResult($idempotencyKey, $result);
                    $this->releaseLock($lockKey);
                    return $result;
                }
                
                // Check expired
                if ($key->isExpired()) {
                    $key->update(['status' => 'expired']);
                    
                    $result = [
                        'valid' => false,
                        'status' => 'expired',
                        'message' => 'Key has expired',
                        'expired_at' => $key->expires_at?->toIso8601String(),
                        'request_id' => $requestId
                    ];
                    
                    $this->logValidation(
                        $key->id,
                        $keyCode,
                        'expired',
                        'Key has expired',
                        $ipAddress,
                        $userAgent,
                        $deviceId
                    );
                    
                    DB::commit();
                    $this->cacheResult($idempotencyKey, $result);
                    $this->releaseLock($lockKey);
                    return $result;
                }
                
                // ✅ KEY IS VALID
                $key->increment('validation_count');
                $key->update(['last_validated_at' => now()]);
                
                $result = [
                    'valid' => true,
                    'status' => 'success',
                    'message' => 'Key is valid',
                    'data' => [
                        'key_id' => $key->id,
                        'product_id' => $key->product_id,
                        'expires_at' => $key->expires_at?->toIso8601String(),
                        'remaining_minutes' => $key->getRemainingMinutes(),
                        'remaining_days' => $key->getRemainingDays(),
                        'validation_count' => $key->validation_count,
                    ],
                    'request_id' => $requestId
                ];
                
                $this->logValidation(
                    $key->id,
                    $keyCode,
                    'success',
                    'Key is valid',
                    $ipAddress,
                    $userAgent,
                    $deviceId,
                    $result['data']
                );
                
                DB::commit();
                
                // Cache successful result
                $this->cacheResult($idempotencyKey, $result);
                
                $processingTime = round((microtime(true) - $startTime) * 1000, 2);
                Log::info("Validation completed successfully", [
                    'request_id' => $requestId,
                    'key_id' => $key->id,
                    'processing_time_ms' => $processingTime
                ]);
                
                $this->releaseLock($lockKey);
                return $result;
                
            } catch (Exception $e) {
                DB::rollBack();
                $this->releaseLock($lockKey);
                throw $e;
            }
            
        } catch (Exception $e) {
            $processingTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error("Validation error", [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'processing_time_ms' => $processingTime
            ]);
            
            return [
                'valid' => false,
                'status' => 'error',
                'message' => 'Internal validation error',
                'request_id' => $requestId
            ];
        }
    }
    
    // ==========================================
    // HELPER: LOG VALIDATION
    // ==========================================
    
    private function logValidation(
        ?int $keyId,
        string $keyCode,
        string $result,
        string $message,
        ?string $ipAddress,
        ?string $userAgent,
        ?string $deviceId,
        ?array $responseData = null
    ): void {
        KeyValidationLog::create([
            'product_key_id' => $keyId,
            'key_code' => $keyCode,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_id' => $deviceId,
            'validation_result' => $result,
            'validation_message' => $message,
            'response_data' => $responseData,
            'validated_at' => now(),
        ]);
    }
    
    // ==========================================
    // ADMIN TOOLS
    // ==========================================
    
    /**
     * Clear all locks (emergency use only)
     */
    public function clearAllLocks(): int
    {
        $count = 0;
        $keys = Cache::get('validation_locks', []);
        
        foreach ($keys as $lockKey) {
            if (Cache::forget($lockKey)) {
                $count++;
            }
        }
        
        Log::warning("All validation locks cleared", ['count' => $count]);
        return $count;
    }
    
    /**
     * Get rate limit stats
     */
    public function getRateLimitStats(string $ipAddress): array
    {
        $ipKey = "rate_limit:ip:{$ipAddress}";
        
        return [
            'ip' => $ipAddress,
            'current_count' => (int) Cache::get($ipKey, 0),
            'limit' => self::MAX_VALIDATIONS_PER_MINUTE,
            'ttl' => Cache::get($ipKey) ? 60 : 0,
        ];
    }
    
    /**
     * Manually suspend suspicious key
     */
    public function suspendSuspiciousKey(string $keyCode, string $reason): bool
    {
        $key = ProductKey::where('key_code', $keyCode)->first();
        
        if (!$key) {
            return false;
        }
        
        $key->suspend("Auto-suspended: {$reason}");
        
        Log::alert("Key suspended for suspicious activity", [
            'key_id' => $key->id,
            'key_code' => $keyCode,
            'reason' => $reason
        ]);
        
        return true;
    }
}