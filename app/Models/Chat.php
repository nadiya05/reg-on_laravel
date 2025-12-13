<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','sender','message','meta'];

    protected $casts = [
        'meta' => 'array',
    ];
// ===== Tambahin ini =====
    protected $appends = ['created_at_formatted'];

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at
            ->timezone('Asia/Jakarta')
            ->format('Y-m-d H:i'); // TANPA DETIK
    }
    // ========================

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function adminUser()
{
    return User::find($this->meta['admin_id'] ?? null);
}
}
