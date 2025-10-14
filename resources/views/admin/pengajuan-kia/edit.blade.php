@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Pengajuan KIA</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('pengajuan-kia.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Jenis KIA --}}
            <div class="mb-3">
                <label for="jenis_kia" class="form-label">Jenis KIA</label>
                <select name="jenis_kia" class="form-control" required>
                    <option value="">-- Pilih Jenis KIA --</option>
                    @foreach($jenisKia as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_kia', $pengajuan->jenis_kia) == $jenis ? 'selected' : '' }}>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_kia') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- NIK Anak --}}
            <div class="mb-3">
                <label for="nik" class="form-label">NIK Anak</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $pengajuan->nik) }}" required>
                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Nama Anak --}}
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Anak</label>
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

            {{-- Dokumen --}}
            <div class="mb-3">
                <label for="kk" class="form-label">Kartu Keluarga</label>
                @if($pengajuan->kk)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $pengajuan->kk) }}" alt="KK" class="img-thumbnail" style="width: 150px;">
                    </div>
                @endif
                <input type="file" name="kk" class="form-control" accept="image/*">
                @error('kk') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="akta_lahir" class="form-label">Akte Kelahiran</label>
                @if($pengajuan->akta_lahir)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $pengajuan->akta_lahir) }}" alt="Akte Lahir" class="img-thumbnail" style="width: 150px;">
                    </div>
                @endif
                <input type="file" name="akta_lahir" class="form-control" accept="image/*">
                @error('akta_lahir') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="surat_nikah" class="form-label">Surat Nikah Orang Tua</label>
                @if($pengajuan->surat_nikah)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $pengajuan->surat_nikah) }}" alt="Surat Nikah" class="img-thumbnail" style="width: 150px;">
                    </div>
                @endif
                <input type="file" name="surat_nikah" class="form-control" accept="image/*">
                @error('surat_nikah') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="ktp_ortu" class="form-label">KTP Orang Tua</label>
                @if($pengajuan->ktp_ortu)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $pengajuan->ktp_ortu) }}" alt="KTP Ortu" class="img-thumbnail" style="width: 150px;">
                    </div>
                @endif
                <input type="file" name="ktp_ortu" class="form-control" accept="image/*">
                @error('ktp_ortu') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="pass_foto" class="form-label">Pas Foto Anak</label>
                @if($pengajuan->pass_foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $pengajuan->pass_foto) }}" alt="Pas Foto" class="img-thumbnail" style="width: 150px;">
                    </div>
                @endif
                <input type="file" name="pass_foto" class="form-control" accept="image/*">
                @error('pass_foto') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('pengajuan-kia.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
