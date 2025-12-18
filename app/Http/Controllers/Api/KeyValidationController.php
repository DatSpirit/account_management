<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\KeyValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class KeyValidationController extends Controller
{
    protected KeyValidationService $validationService;

    public function __construct(KeyValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * SECURE VALIDATION ENDPOINT
     * 
     * Endpoint: POST /api/v2/validate-key
     * 
     * Features:
     * - Cache Atomic Locking (Layer 1)
     * - Database Pessimistic Locking (Layer 2)
     * - Idempotency Protection (Layer 3)
     * - Rate Limiting & Anti-Spam (Layer 4)
     * 
     * @bodyParam key_code string required The key code to validate (max 100 chars)
     * @bodyParam device_id string optional Unique device identifier (max 255 chars)
     * @bodyParam request_id string optional Client-side request ID for tracking
     */
    public function validate(Request $request): JsonResponse
    {
        // Validate input
        $validated = $request->validate([
            'key_code' => 'required|string|max:100',
            'device_id' => 'nullable|string|max:255',
            'request_id' => 'nullable|string|max:50',
        ]);

        try {
            $result = $this->validationService->validateKey(
                keyCode: $validated['key_code'],
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
                deviceId: $validated['device_id'] ?? null
            );

            // Return appropriate status code
            $statusCode = match($result['status']) {
                'success' => 200,
                'rate_limited', 'suspicious' => 429,
                'invalid', 'expired', 'suspended' => 403,
                default => 500,
            };

            return response()->json($result, $statusCode);

        } catch (\Exception $e) {
            Log::error('Validation API Error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'key_code' => substr($validated['key_code'], 0, 8) . '***'
            ]);

            return response()->json([
                'valid' => false,
                'status' => 'error',
                'message' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * BATCH VALIDATION (with limits)
     * 
     * Endpoint: POST /api/v2/validate-keys/batch
     * Max: 5 keys per request
     */
    public function batchValidate(Request $request): JsonResponse
    {
        $request->validate([
            'key_codes' => 'required|array|max:5', // Limit to 5 keys
            'key_codes.*' => 'required|string|max:100',
            'device_id' => 'nullable|string|max:255',
        ]);

        $results = [];
        $validCount = 0;
        $invalidCount = 0;

        foreach ($request->key_codes as $keyCode) {
            $result = $this->validationService->validateKey(
                keyCode: $keyCode,
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
                deviceId: $request->device_id
            );

            $results[$keyCode] = $result;

            if ($result['valid']) {
                $validCount++;
            } else {
                $invalidCount++;
            }
        }

        return response()->json([
            'success' => true,
            'summary' => [
                'total' => count($results),
                'valid' => $validCount,
                'invalid' => $invalidCount,
            ],
            'results' => $results,
        ]);
    }

    /**
     * KEY INFO (without validation)
     * 
     * Endpoint: GET /api/v2/key-info/{key_code}
     * Does not increment validation counter
     */
    public function info(string $keyCode): JsonResponse
    {
        $key = \App\Models\ProductKey::where('key_code', $keyCode)->first();

        if (!$key) {
            return response()->json([
                'success' => false,
                'message' => 'Key not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'key_code' => $key->key_code,
                'status' => $key->status,
                'expires_at' => $key->expires_at?->toIso8601String(),
                'remaining_minutes' => $key->getRemainingMinutes(),
                'remaining_days' => $key->getRemainingDays(),
                'is_active' => $key->isActive(),
                'is_expired' => $key->isExpired(),
                'validation_count' => $key->validation_count,
                'last_validated_at' => $key->last_validated_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * HEALTH CHECK
     * 
     * Endpoint: GET /api/v2/validate-key/health
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'PayKMS Enhanced Key Validation v2',
            'timestamp' => now()->toIso8601String(),
            'features' => [
                'cache_locking' => true,
                'pessimistic_locking' => true,
                'idempotency' => true,
                'rate_limiting' => true,
                'anti_spam' => true,
            ],
        ]);
    }

    /**
     * RATE LIMIT STATUS
     * 
     * Endpoint: GET /api/v2/rate-limit-status
     * Check current rate limit status for requester
     */
    public function rateLimitStatus(Request $request): JsonResponse
    {
        $stats = $this->validationService->getRateLimitStats($request->ip());

        return response()->json([
            'success' => true,
            'rate_limit' => $stats,
        ]);
    }

    /**
     * ADMIN: Clear All Locks
     * 
     * Endpoint: POST /api/v2/admin/clear-locks
     * Emergency use only - requires admin authentication
     */
    public function clearLocks(Request $request): JsonResponse
    {
        // Add admin authentication check here
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $count = $this->validationService->clearAllLocks();

        return response()->json([
            'success' => true,
            'message' => "Cleared {$count} locks",
            'count' => $count,
        ]);
    }

    /**
     * ADMIN: Suspend Suspicious Key
     * 
     * Endpoint: POST /api/v2/admin/suspend-key
     */
    public function suspendKey(Request $request): JsonResponse
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'key_code' => 'required|string',
            'reason' => 'required|string|max:500',
        ]);

        $success = $this->validationService->suspendSuspiciousKey(
            $request->key_code,
            $request->reason
        );

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Key suspended successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Key not found',
        ], 404);
    }
}