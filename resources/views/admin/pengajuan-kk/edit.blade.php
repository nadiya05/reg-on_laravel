@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Pengajuan KK</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('pengajuan-kk.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Jenis KK --}}
            <div class="mb-3">
                <label for="jenis_kk" class="form-label">Jenis KK</label>
                <select name="jenis_kk" id="jenis_kk" class="form-control" required>
                    <option value="">-- Pilih Jenis KK --</option>
                    @foreach($jenisKk as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_kk', $pengajuan->jenis_kk) == $jenis ? 'selected' : '' }}>
                            {{ ucfirst($jenis) }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_kk') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- NIK --}}
            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $pengajuan->nik) }}" required>
                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Nama --}}
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $pengajuan->nama) }}" required>
                @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Tanggal Pengajuan --}}
            <div class="mb-3">
                <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan</label>
                <input type="date" name="tanggal_pengajuan" class="form-control"
                       value="{{ old('tanggal_pengajuan', $pengajuan->tanggal_pengajuan) }}" required>
                @error('tanggal_pengajuan') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Dokumen untuk Pemula --}}
            <div id="dokumen-pemula" style="display:none;">
                <div class="mb-3">
                    <label class="form-label">Formulir Permohonan KK</label>
                    @if($pengajuan->formulir_permohonan_kk)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $pengajuan->formulir_permohonan_kk) }}" class="img-thumbnail" style="width:150px;">
                        </div>
                    @endif
                    <input type="file" name="formulir_permohonan_kk" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Surat Nikah</label>
                    @if($pengajuan->surat_nikah)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $pengajuan->surat_nikah) }}" class="img-thumbnail" style="width:150px;">
                        </div>
                    @endif
                    <input type="file" name="surat_nikah" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Surat Keterangan Pindah Datang</label>
                    @if($pengajuan->surat_keterangan_pindah)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $pengajuan->surat_keterangan_pindah) }}" class="img-thumbnail" style="width:150px;">
                        </div>
                    @endif
                    <input type="file" name="surat_keterangan_pindah" class="form-control" accept="image/*">
                </div>
            </div>

            {{-- Dokumen untuk Ubah Status --}}
            <div id="dokumen-ubah-status" style="display:none;">
                <div class="mb-3">
                    <label class="form-label">KK Asli</label>
                    @if($pengajuan->kk_asli)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $pengajuan->kk_asli) }}" class="img-thumbnail" style="width:150px;">
                        </div>
                    @endif
                    <input type="file" name="kk_asli" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Surat Kematian</label>
                    @if($pengajuan->surat_kematian)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $pengajuan->surat_kematian) }}" class="img-thumbnail" style="width:150px;">
                        </div>
                    @endif
                    <input type="file" name="surat_kematian" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Akta Kelahiran</label>
                    @if($pengajuan->akta_kelahiran)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $pengajuan->akta_kelahiran) }}" class="img-thumbnail" style="width:150px;">
                        </div>
                    @endif
                    <input type="file" name="akta_kelahiran" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ijazah</label>
                    @if($pengajuan->ijazah)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $pengajuan->ijazah) }}" class="img-thumbnail" style="width:150px;">
                        </div>
                    @endif
                    <input type="file" name="ijazah" class="form-control" accept="image/*">
                </div>
            </div>

            {{-- Tombol --}}
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('pengajuan-kk.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

{{-- Script JS untuk tampilkan dokumen sesuai jenis --}}
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
    toggleDokumen(); // langsung tampil sesuai data lama
});
</script>
@endsection
