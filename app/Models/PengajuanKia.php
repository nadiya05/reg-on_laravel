<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanKia extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_kia';

    protected $fillable = [
        'status', 
        'user_id', 
        'nomor_antrean', 
        'jenis_kia', 
        'nik', 
        'nama', 
        'tanggal_pengajuan',
        'kk', 
        'akta_lahir', 
        'surat_nikah', 
        'ktp_ortu', 
        'pass_foto',
    ];

    /**
     * Ambil opsi enum dari kolom jenis_kia di database
     */
    public static function getJenisKiaOptions()
    {
        $row = DB::select("SHOW COLUMNS FROM pengajuan_kia WHERE Field='jenis_kia'")[0];
        preg_match("/^enum\((.*)\)$/", $row->Type, $matches);
        $values = array_map(fn($val) => trim($val, "'"), explode(',', $matches[1]));
        return $values;
    }

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Generate nomor antrean otomatis berdasarkan tanggal pengajuan
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $today = now()->toDateString();

            // Ambil antrean terakhir untuk hari ini
            $last = self::whereDate('tanggal_pengajuan', $today)
                ->orderBy('nomor_antrean', 'desc')
                ->first();

            if ($last && is_numeric($last->nomor_antrean)) {
                $nextNumber = str_pad(((int) $last->nomor_antrean) + 1, 3, "0", STR_PAD_LEFT);
            } else {
                $nextNumber = "001"; // Reset antrean di awal hari
            }

            $model->nomor_antrean = $nextNumber;

            // Set tanggal pengajuan ke hari ini jika belum diisi
            if (!$model->tanggal_pengajuan) {
                $model->tanggal_pengajuan = $today;
            }
        });
    }
}
