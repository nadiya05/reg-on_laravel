@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Berita</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.kelola-berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Berita</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $berita->judul) }}" required>
                @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Tanggal --}}
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $berita->tanggal) }}" required>
                @error('tanggal') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                @if($berita->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto Berita" style="width: 150px; height: 100px; object-fit: cover; border-radius: 5px;">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Konten --}}
            <div class="mb-3">
                <label for="konten" class="form-label">Konten</label>
                <textarea name="konten" id="konten" class="form-control" rows="8" placeholder="Tulis konten berita di sini...">{{ old('konten', $berita->konten) }}</textarea>
                @error('konten') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.kelola-berita.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    {{-- Jika ingin pakai editor seperti Summernote --}}
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
