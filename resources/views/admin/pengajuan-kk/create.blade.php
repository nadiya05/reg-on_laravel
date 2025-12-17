@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Tambah Pengajuan KK</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('pengajuan-kk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Jenis KK --}}
            <div class="mb-3">
                <label class="form-label">Jenis KK</label>
                <select name="jenis_kk" id="jenis_kk" class="form-control" required>
                    <option value="">-- Pilih Jenis KK --</option>
                    @foreach($jenisKk as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_kk') == $jenis ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $jenis)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Field Wajib --}}
            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Pengajuan</label>
                <input type="date" name="tanggal_pengajuan" class="form-control" value="{{ old('tanggal_pengajuan') }}" required>
            </div>

            {{-- Formulir KK selalu ada --}}
            <div class="mb-3">
                <label class="form-label">Formulir Permohonan KK</label>
                <input type="file" name="formulir_permohonan_kk" class="form-control" accept="image/*">
            </div>

            {{-- Dokumen Section --}}
            @php
                $sections = [
                    'pendidikan' => ['ijazah'],
                    'status_perkawinan' => ['surat_nikah'],
                    'perceraian' => ['akta_cerai'],
                    'kematian' => ['surat_kematian'],
                    'gol_darah' => ['bukti_cek_darah'],
                    'penambahan_anggota' => ['akta_kelahiran'],
                    'pindahan' => ['surat_keterangan_pindah'],
                    'pisah_kk' => ['surat_pisah_kk'],
                ];
            @endphp

            @foreach($sections as $key => $files)
                <div id="dokumen-{{ $key }}" class="dokumen-section d-none">
                    @foreach($files as $file)
                        <div class="mb-3">
                            <label class="form-label">{{ ucwords(str_replace('_', ' ', $file)) }}</label>
                            <input type="file" name="{{ $file }}" class="form-control">
                        </div>
                    @endforeach
                </div>
            @endforeach

            {{-- Tombol --}}
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pengajuan-kk.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('jenis_kk');
    const sections = document.querySelectorAll('.dokumen-section');

    function toggleDokumen() {
        sections.forEach(s => s.classList.add('d-none'));
        if(select.value){
            const target = document.getElementById('dokumen-'+select.value);
            if(target) target.classList.remove('d-none');
        }
    }

    select.addEventListener('change', toggleDokumen);
    toggleDokumen(); // auto tampil sesuai old value
});
</script>
@endsection
