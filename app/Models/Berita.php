<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    // Nama tabel (opsional, kalau nama tabel = jamak dari model, bisa di-skip)
    protected $table = 'berita';

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'judul',
        'tanggal',
        'foto',
        'konten',
    ];
}
