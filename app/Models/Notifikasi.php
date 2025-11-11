<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tanggal',
        'status',
        'tipe_pengajuan',
        'pengajuan_id',
    ];

    // Relasi ke user (akun yang login)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 🔹 Relasi dinamis ke model pengajuan
     * Tidak bisa pakai switch di return,
     * tapi bisa kita akali dengan accessor.
     */
    public function getPengajuanDataAttribute()
    {
        switch (strtoupper($this->tipe_pengajuan)) {
            case 'KTP':
                return \App\Models\PengajuanKtp::find($this->pengajuan_id);
            case 'KK':
                return \App\Models\PengajuanKk::find($this->pengajuan_id);
            case 'KIA':
                return \App\Models\PengajuanKia::find($this->pengajuan_id);
            default:
                return null;
        }
    }

    /**
     * 🔹 Accessor untuk ambil nama pengajuan (otomatis)
     */
    public function getNamaPengajuanAttribute()
    {
        return $this->pengajuan_data->nama ?? '-';
    }

    /**
     * 🔹 Accessor untuk ambil jenis dokumen (otomatis)
     */
    public function getJenisDokumenAttribute()
    {
        if (!$this->pengajuan_data) return '-';

        switch (strtoupper($this->tipe_pengajuan)) {
            case 'KTP':
                return $this->pengajuan_data->jenis_ktp ?? '-';
            case 'KK':
                return $this->pengajuan_data->jenis_kk ?? '-';
            case 'KIA':
                return $this->pengajuan_data->jenis_kia ?? '-';
            default:
                return '-';
        }
    }
}
