<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyHistory extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_key_id', 'action', 'description', 'meta_data'];
    protected $casts = ['meta_data' => 'array', 'created_at' => 'datetime'];

    // Hàm helper để ghi log nhanh
    public static function log($keyId, $action, $desc, $meta = [])
    {
        self::create([
            'product_key_id' => $keyId,
            'action' => $action,
            'description' => $desc,
            'meta_data' => $meta
        ]);
    }
}
