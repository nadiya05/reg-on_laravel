```blade
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Tambah Informasi Baru</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.kelola-informasi.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="jenis_pengajuan" class="form-label">Jenis Pengajuan</label>
                <input type="text" name="jenis_pengajuan" class="form-control" placeholder="Masukkan jenis pengajuan" required>
            </div>

            <div class="mb-3">
                <label for="jenis_dokumen" class="form-label">Jenis Dokumen</label>
                <input type="text" name="jenis_dokumen" class="form-control" placeholder="Masukkan jenis dokumen" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.kelola-informasi') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
```
