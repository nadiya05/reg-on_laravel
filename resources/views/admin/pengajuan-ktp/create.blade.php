@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Tambah Pengajuan KTP</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.pengajuan-ktp.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Jenis KTP --}}
            <div class="mb-3">
                <label for="jenis_ktp" class="form-label">Jenis KTP</label>
                <select name="jenis_ktp" id="jenis_ktp" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    @foreach($jenisKtp as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_ktp') == $jenis ? 'selected' : '' }}>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_ktp') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- NIK --}}
            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Nama --}}
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Tanggal Pengajuan --}}
            <div class="mb-3">
                <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan</label>
                <input type="date" name="tanggal_pengajuan" class="form-control" value="{{ old('tanggal_pengajuan') }}" required>
                @error('tanggal_pengajuan') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Dokumen Dinamis --}}
            <div id="dokumen-section">
                {{-- Field dokumen akan muncul otomatis sesuai jenis KTP --}}
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            <a href="{{ route('admin.pengajuan-ktp.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
</div>

{{-- SCRIPT untuk menampilkan dokumen sesuai pilihan --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jenisSelect = document.getElementById('jenis_ktp');
        const dokumenSection = document.getElementById('dokumen-section');

        function renderDokumen(jenis) {
            dokumenSection.innerHTML = ''; // reset
            let html = '';

            if (jenis === 'Pemula') {
                html = `
                    <div class="mb-3">
                        <label for="kk" class="form-label">Kartu Keluarga</label>
                        <input type="file" name="kk" class="form-control" accept="image/*" required>
                        @error('kk') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="ijazah_skl" class="form-label">Ijazah / SKL</label>
                        <input type="file" name="ijazah_skl" class="form-control" accept="image/*" required>
                        @error('ijazah_skl') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                `;
            } else if (jenis === 'Kehilangan') {
                html = `
                    <div class="mb-3">
                        <label for="kk" class="form-label">Kartu Keluarga</label>
                        <input type="file" name="kk" class="form-control" accept="image/*" required>
                        @error('kk') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="surat_kehilangan" class="form-label">Surat Kehilangan dari Kepolisian</label>
                        <input type="file" name="surat_kehilangan" class="form-control" accept="image/*" required>
                        @error('surat_kehilangan') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                `;
            } else if (jenis === 'Rusak atau Ubah Status') {
                html = `
                    <div class="mb-3">
                        <label for="kk" class="form-label">Kartu Keluarga (yang sudah diperbarui)</label>
                        <input type="file" name="kk" class="form-control" accept="image/*" required>
                        @error('kk') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                `;
            }

            dokumenSection.innerHTML = html;
        }

        // Kalau sudah ada value sebelumnya (misalnya validasi gagal)
        if (jenisSelect.value) {
            renderDokumen(jenisSelect.value);
        }

        // Ubah tampilan dokumen setiap kali pilihan berubah
        jenisSelect.addEventListener('change', function() {
            renderDokumen(this.value);
        });
    });
</script>
@endsection
