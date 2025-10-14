<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanKtp extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_ktp';

    protected $fillable = [
        'status', 'user_id', 'nomor_antrean', 'jenis_ktp', 'nik', 'nama', 'tanggal_pengajuan',
        'kk', 'ijazah_skl', 'surat_kehilangan'
    ];

    public static function getJenisKtpOptions()
    {
        $row = DB::select("SHOW COLUMNS FROM pengajuan_ktp WHERE Field='jenis_ktp'")[0];
        preg_match("/^enum\((.*)\)$/", $row->Type, $matches);
        $values = array_map(fn($val) => trim($val, "'"), explode(',', $matches[1]));
        return $values;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $today = now()->toDateString();

        // Ambil antrean terakhir HARI INI
        $last = self::whereDate('tanggal_pengajuan', $today)
            ->orderBy('nomor_antrean', 'desc')
            ->first();

        if ($last && is_numeric($last->nomor_antrean)) {
            $nextNumber = str_pad(((int) $last->nomor_antrean) + 1, 3, "0", STR_PAD_LEFT);
        } else {
            $nextNumber = "001"; // Reset awal hari
        }

        $model->nomor_antrean = $nextNumber;

        // Pastikan tanggal_pengajuan keisi hari ini kalau belum ada
        if (!$model->tanggal_pengajuan) {
            $model->tanggal_pengajuan = $today;
        }
    });
}
}