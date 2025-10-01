<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    protected $table = 'informasi';
    protected $fillable = ['jenis_pengajuan', 'jenis_dokumen', 'deskripsi'];
}
