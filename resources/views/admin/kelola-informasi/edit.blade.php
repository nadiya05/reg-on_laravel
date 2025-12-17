@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Informasi</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.kelola-informasi.update', $info->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Jenis Dokumen --}}
            <div class="mb-3">
                <label class="form-label">Jenis Dokumen</label>
                <select id="jenis_dokumen" name="jenis_dokumen" class="form-control" required>
                    <option value="">-- Pilih Jenis Dokumen --</option>
                    @foreach($dokumenOptions as $dok)
                        <option value="{{ $dok }}"
                            {{ old('jenis_dokumen', $info->jenis_dokumen) == $dok ? 'selected' : '' }}>
                            {{ $dok }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_dokumen') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Jenis Pengajuan --}}
            <div class="mb-3">
                <label class="form-label">Jenis Pengajuan</label>
                <select id="jenis_pengajuan" name="jenis_pengajuan" class="form-control" required>
                    <option value="">-- Pilih Jenis Pengajuan --</option>
                </select>
                @error('jenis_pengajuan') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $info->deskripsi) }}</textarea>
                @error('deskripsi') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.kelola-informasi') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    const jenisPengajuanMap = @json($jenisPengajuanByDokumen);

    const dokumenSelect   = document.getElementById('jenis_dokumen');
    const pengajuanSelect = document.getElementById('jenis_pengajuan');

    function loadJenisPengajuan(dokumen, selectedValue = null) {
        pengajuanSelect.innerHTML = '<option value="">-- Pilih Jenis Pengajuan --</option>';

        if (jenisPengajuanMap[dokumen]) {
            jenisPengajuanMap[dokumen].forEach(item => {
                const option = document.createElement('option');
                option.value = item;
                option.textContent = item;

                if (selectedValue === item) {
                    option.selected = true;
                }

                pengajuanSelect.appendChild(option);
            });
        }
    }

    // Saat halaman edit dibuka
    loadJenisPengajuan(
        "{{ old('jenis_dokumen', $info->jenis_dokumen) }}",
        "{{ old('jenis_pengajuan', $info->jenis_pengajuan) }}"
    );

    // Saat dokumen diganti
    dokumenSelect.addEventListener('change', function () {
        loadJenisPengajuan(this.value);
    });
</script>
@endsection
