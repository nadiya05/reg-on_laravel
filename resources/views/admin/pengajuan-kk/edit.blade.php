@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Pengajuan KK</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('pengajuan-kk.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ================= Jenis KK ================= --}}
            <div class="mb-3">
                <label class="form-label">Jenis KK</label>
                <select name="jenis_kk" id="jenis_kk" class="form-control" required>
                    <option value="">-- Pilih Jenis KK --</option>
                    @foreach($jenisKk as $jenis)
                        <option value="{{ $jenis }}"
                            {{ old('jenis_kk', $pengajuan->jenis_kk) == $jenis ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $jenis)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ================= FIELD WAJIB ================= --}}
            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control"
                       value="{{ old('nik', $pengajuan->nik) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control"
                       value="{{ old('nama', $pengajuan->nama) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Pengajuan</label>
                <input type="date" name="tanggal_pengajuan" class="form-control"
                       value="{{ old('tanggal_pengajuan', $pengajuan->tanggal_pengajuan) }}" required>
            </div>

            {{-- ================= FORMULIR KK (SELALU ADA) ================= --}}
            <div class="mb-3">
                <label class="form-label">Formulir Permohonan KK</label>
                @if($pengajuan->formulir_permohonan_kk)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $pengajuan->formulir_permohonan_kk) }}" target="_blank">
                            Lihat Dokumen Lama
                        </a>
                    </div>
                @endif
                <input type="file" name="formulir_permohonan_kk" class="form-control">
            </div>

            {{-- ================= DOKUMEN DINAMIS ================= --}}
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
                            {{-- Hanya tampilkan dokumen lama jika section ini aktif --}}
                            <div class="old-file mb-2" style="display:none;">
                                @if(!empty($pengajuan->$file))
                                    <a href="{{ asset('storage/' . $pengajuan->$file) }}" target="_blank">
                                        Lihat Dokumen Lama
                                    </a>
                                @endif
                            </div>
                            <input type="file" name="{{ $file }}" class="form-control">
                        </div>
                    @endforeach
                </div>
            @endforeach

            {{-- ================= TOMBOL ================= --}}
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('pengajuan-kk.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

{{-- ================= SCRIPT TOGGLE ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('jenis_kk');
    const sections = document.querySelectorAll('.dokumen-section');

    function toggleDokumen() {
        sections.forEach(s => {
            s.classList.add('d-none');
            s.querySelectorAll('.old-file').forEach(f => f.style.display = 'none'); // hide old files
        });

        if (select.value) {
            const target = document.getElementById('dokumen-' + select.value);
            if (target) {
                target.classList.remove('d-none');
                target.querySelectorAll('.old-file').forEach(f => f.style.display = 'block'); // show old files
            }
        }
    }

    select.addEventListener('change', toggleDokumen);
    toggleDokumen(); // tampil sesuai jenis KK lama
});
</script>
@endsection
