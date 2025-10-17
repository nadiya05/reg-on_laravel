@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Tambah Berita Baru</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.kelola-berita.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Judul --}}
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Berita</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="Masukkan judul berita" required>
                @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Tanggal --}}
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                @error('tanggal') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
                <small class="text-muted">Opsional — Maks 2MB (jpg, jpeg, png)</small>
                @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Konten --}}
            <div class="mb-3">
                <label for="konten" class="form-label">Konten Berita</label>
                <textarea name="konten" id="konten" class="form-control" rows="8" placeholder="Tulis konten berita di sini...">{{ old('konten') }}</textarea>
                @error('konten') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.kelola-berita.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    {{-- Optional: Summernote editor --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script>
        $('#konten').summernote({
            placeholder: 'Tulis konten berita...',
            tabsize: 2,
            height: 250
        });
    </script>
@endsection
