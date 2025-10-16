@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Tambah Pengajuan KK</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('pengajuan-kk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Jenis KK --}}
            <div class="mb-3">
                <label for="jenis_kk" class="form-label">Jenis KK</label>
                <select name="jenis_kk" id="jenis_kk" class="form-control" required>
                    <option value="">-- Pilih Jenis KK --</option>
                    @foreach($jenisKk as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_kk') == $jenis ? 'selected' : '' }}>
                            {{ ucfirst($jenis) }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_kk') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- NIK --}}
            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Nama --}}
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Tanggal Pengajuan --}}
            <div class="mb-3">
                <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan</label>
                <input type="date" name="tanggal_pengajuan" class="form-control" value="{{ old('tanggal_pengajuan') }}" required>
                @error('tanggal_pengajuan') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Dokumen --}}
            <div id="dokumen-pemula" style="display:none;">
                <div class="mb-3">
                    <label for="formulir_permohonan_kk" class="form-label">Formulir Permohonan KK</label>
                    <input type="file" name="formulir_permohonan_kk" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="surat_nikah" class="form-label">Surat Nikah</label>
                    <input type="file" name="surat_nikah" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="surat_keterangan_pindah" class="form-label">Surat Keterangan Pindah Datang</label>
                    <input type="file" name="surat_keterangan_pindah" class="form-control" accept="image/*">
                </div>
            </div>

            <div id="dokumen-ubah-status" style="display:none;">
                <div class="mb-3">
                    <label for="kk_asli" class="form-label">KK Asli</label>
                    <input type="file" name="kk_asli" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="surat_kematian" class="form-label">Surat Kematian</label>
                    <input type="file" name="surat_kematian" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="akta_kelahiran" class="form-label">Akta Kelahiran</label>
                    <input type="file" name="akta_kelahiran" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="ijazah" class="form-label">Ijazah</label>
                    <input type="file" name="ijazah" class="form-control" accept="image/*">
                </div>
            </div>

            {{-- Tombol --}}
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pengajuan-kk.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

{{-- Script untuk menampilkan dokumen sesuai pilihan --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const jenisSelect = document.getElementById('jenis_kk');
    const pemulaSection = document.getElementById('dokumen-pemula');
    const ubahStatusSection = document.getElementById('dokumen-ubah-status');

    function toggleDokumen() {
        const value = jenisSelect.value.toLowerCase();
        pemulaSection.style.display = value === 'pemula' ? 'block' : 'none';
        ubahStatusSection.style.display = value === 'ubah status' ? 'block' : 'none';
    }

    jenisSelect.addEventListener('change', toggleDokumen);
    toggleDokumen(); // biar langsung sesuai kalau old value ada
});
</script>
@endsection
