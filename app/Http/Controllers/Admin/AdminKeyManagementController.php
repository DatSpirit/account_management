<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductKey;
use App\Models\User;
use App\Services\KeyManagementService;
use Illuminate\Http\Request;

class AdminKeyManagementController extends Controller
{
    protected KeyManagementService $keyService;

    public static $middleware = [
        'auth',
        'admin',
    ];


    public function __construct(KeyManagementService $keyService)
    {
        $this->keyService = $keyService;
    }

    /**
     * Danh sách tất cả key (Admin)
     */
    public function index(Request $request)
    {
        $query = ProductKey::with(['user', 'product']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('key_code', 'like', '%' . $request->search . '%')
                    ->orWhere('assigned_to_email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('key_type')) {
            $query->where('key_type', $request->key_type);
        }

        $keys = $query->orderBy('created_at', 'desc')->paginate(50);

        // Stats
        $stats = [
            'total' => ProductKey::count(),
            'active' => ProductKey::active()->count(),
            'expired' => ProductKey::expired()->count(),
            'suspended' => ProductKey::where('status', 'suspended')->count(),
            'expiring_soon' => ProductKey::expiringSoon(7)->count(),
            'total_validations' => ProductKey::sum('validation_count'),
            'total_spent' => ProductKey::sum('key_cost'),
        ];

        return view('admin.keys.index', compact('keys', 'stats'));
    }

    /**
     * Chi tiết key (Admin view)
     */
    public function show($id)
    {

        $key = ProductKey::with(['user', 'product'])->findOrFail($id);

        $recentValidations = $key->validationLogs()
            ->orderBy('validated_at', 'desc')
            ->limit(10)
            ->get();

        $validationStats = [
            'total_validations' => $key->validation_count,
            'success_count' => $key->validationLogs()->success()->count(),
            'failed_count' => $key->validationLogs()->failed()->count(),
            'unique_ips' => $key->validationLogs()->distinct('ip_address')->count('ip_address'),
        ];

        return view('admin.keys.show', compact(
            'key',
            'recentValidations',
            'validationStats'
        ));
    }

    /**
     * Suspend key (Admin)
     */
    public function suspend(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $key = ProductKey::findOrFail($id);
        $key->suspend($request->reason);

        return back()->with('success', 'Key suspended successfully');
    }

    /**
     * Activate key (Admin)
     */
    public function activate($id)
    {
        $key = ProductKey::findOrFail($id);

        if ($key->isExpired()) {
            return back()->with('error', 'Cannot activate expired key. Please extend it first.');
        }

        $key->update(['status' => 'active']);

        return back()->with('success', 'Key activated successfully');
    }

    /**
     * Revoke key (Admin)
     */
    public function revoke(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $key = ProductKey::findOrFail($id);
        $key->revoke($request->reason);

        return back()->with('success', 'Key revoked successfully');
    }

    /**
     * Extend key (Admin - free of charge)
     */
    public function extendAdmin(Request $request, $id)
    {
        $request->validate([
            'additional_minutes' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
        ]);

        $key = ProductKey::findOrFail($id);
        $key->extend($request->additional_minutes);
        $key->notes = ($key->notes ?? '') . "\nAdmin extended: {$request->reason}";
        $key->save();

        return back()->with('success', 'Key extended successfully');
    }

    /**
     * Delete key (Admin)
     */
    public function destroy($id)
    {
        $key = ProductKey::findOrFail($id);
        $key->delete();

        return redirect()
            ->route('admin.keys.index')
            ->with('success', 'Key deleted successfully');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:suspend,activate,revoke,delete',
            'key_ids' => 'required|array',
            'key_ids.*' => 'exists:product_keys,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $keys = ProductKey::whereIn('id', $request->key_ids)->get();

        foreach ($keys as $key) {
            switch ($request->action) {
                case 'suspend':
                    $key->suspend($request->reason);
                    break;
                case 'activate':
                    if (!$key->isExpired()) {
                        $key->update(['status' => 'active']);
                    }
                    break;
                case 'revoke':
                    $key->revoke($request->reason);
                    break;
                case 'delete':
                    $key->delete();
                    break;
            }
        }

        return back()->with('success', "Bulk action completed for " . count($keys) . " keys");
    }

    /**
     * Export keys to CSV
     */
    public function export(Request $request)
    {
        $query = ProductKey::with(['user', 'product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $keys = $query->orderBy('created_at', 'desc')->get();

        $filename = 'product_keys_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($keys) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Headers
            fputcsv($file, [
                'ID',
                'Key Code',
                'Type',
                'User Email',
                'Product',
                'Status',
                'Duration (Minutes)',
                'Coinkey Cost',
                'Activated At',
                'Expires At',
                'Validation Count',
                'Created At',
            ]);

            foreach ($keys as $key) {
                fputcsv($file, [
                    $key->id,
                    $key->key_code,
                    ucfirst($key->key_type),
                    $key->user->email ?? 'N/A',
                    $key->product->name ?? 'N/A',
                    ucfirst($key->status),
                    $key->duration_minutes,
                    number_format($key->coinkey_cost, 2),
                    $key->activated_at?->format('Y-m-d H:i:s') ?? 'Not activated',
                    $key->expires_at?->format('Y-m-d H:i:s') ?? 'Never',
                    $key->validation_count,
                    $key->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Validation statistics
     */
    public function validationStats()
    {
        $stats = [
            'total_validations' => \App\Models\KeyValidationLog::count(),
            'success_validations' => \App\Models\KeyValidationLog::success()->count(),
            'failed_validations' => \App\Models\KeyValidationLog::failed()->count(),
            'unique_ips' => \App\Models\KeyValidationLog::distinct('ip_address')->count('ip_address'),
            'validations_today' => \App\Models\KeyValidationLog::whereDate('validated_at', today())->count(),
        ];

        // Top validated keys
        $topKeys = ProductKey::withCount('validationLogs')
            ->orderBy('validation_logs_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.keys.validation-stats', compact('stats', 'topKeys'));
    }
}
