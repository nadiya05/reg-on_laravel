@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Informasi</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.kelola-informasi.update', $info->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Jenis Pengajuan --}}
            <div class="mb-3">
                <label for="jenis_pengajuan" class="form-label">Jenis Pengajuan</label>
                <select name="jenis_pengajuan" class="form-control" required>
                    <option value="">-- Pilih Jenis Pengajuan --</option>
                    @foreach($pengajuanOptions as $option)
                        <option value="{{ $option }}" {{ old('jenis_pengajuan', $info->jenis_pengajuan) == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_pengajuan') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Jenis Dokumen --}}
            <div class="mb-3">
                <label for="jenis_dokumen" class="form-label">Jenis Dokumen</label>
                <select name="jenis_dokumen" class="form-control" required>
                    <option value="">-- Pilih Jenis Dokumen --</option>
                    @foreach($dokumenOptions as $option)
                        <option value="{{ $option }}" {{ old('jenis_dokumen', $info->jenis_dokumen) == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_dokumen') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi">{{ old('deskripsi', $info->deskripsi) }}</textarea>
                @error('deskripsi') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.kelola-informasi') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
