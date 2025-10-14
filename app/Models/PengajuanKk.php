<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanKk extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_kk';

    protected $fillable = [
        'status',
        'user_id',
        'nomor_antrean',
        'jenis_kk',
        'nik',
        'nama',
        'tanggal_pengajuan',
        'formulir_permohonan_kk',
        'surat_nikah',
        'surat_keterangan_pindah',
        'kk_asli',
        'surat_kematian',
        'akta_kelahiran',
        'ijazah',
    ];

    /**
     * Ambil opsi enum dari kolom jenis_kk di database
     */
    public static function getJenisKkOptions()
    {
        $row = DB::select("SHOW COLUMNS FROM pengajuan_kk WHERE Field='jenis_kk'")[0];
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
