@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Tambah Informasi Baru</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.kelola-informasi.store') }}" method="POST">
            @csrf

            {{-- Jenis Dokumen --}}
            <div class="mb-3">
                <label class="form-label">Jenis Dokumen</label>
                <select id="jenis_dokumen" name="jenis_dokumen" class="form-control" required>
                    <option value="">-- Pilih Jenis Dokumen --</option>
                    @foreach($dokumenOptions as $dok)
                        <option value="{{ $dok }}">{{ $dok }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Jenis Pengajuan (DINAMIS) --}}
            <div class="mb-3">
                <label class="form-label">Jenis Pengajuan</label>
                <select id="jenis_pengajuan" name="jenis_pengajuan" class="form-control" required>
                    <option value="">-- Pilih Jenis Dokumen Terlebih Dahulu --</option>
                </select>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.kelola-informasi') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    const jenisPengajuanMap = @json($jenisPengajuanByDokumen);

    const dokumenSelect = document.getElementById('jenis_dokumen');
    const pengajuanSelect = document.getElementById('jenis_pengajuan');

    dokumenSelect.addEventListener('change', function () {
        pengajuanSelect.innerHTML = '<option value="">-- Pilih Jenis Pengajuan --</option>';

        if (jenisPengajuanMap[this.value]) {
            jenisPengajuanMap[this.value].forEach(item => {
                const option = document.createElement('option');
                option.value = item;
                option.textContent = item;
                pengajuanSelect.appendChild(option);
            });
        }
    });
</script>
@endsection
