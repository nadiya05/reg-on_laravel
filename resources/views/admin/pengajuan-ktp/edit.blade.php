@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Pengajuan KTP</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.pengajuan-ktp.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="jenis_ktp" class="form-label">Jenis KTP</label>
                <select name="jenis_ktp" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    @foreach($jenisKtp as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_ktp', $pengajuan->jenis_ktp) == $jenis ? 'selected' : '' }}>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_ktp') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $pengajuan->nik) }}" required>
                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $pengajuan->nama) }}" required>
                @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan</label>
                <input type="date" name="tanggal_pengajuan" class="form-control" value="{{ old('tanggal_pengajuan', $pengajuan->tanggal_pengajuan) }}" required>
                @error('tanggal_pengajuan') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Dokumen --}}
            <div class="mb-3">
                <label for="kk" class="form-label">Kartu Keluarga</label>
                @if($pengajuan->kk)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $pengajuan->kk) }}" 
                             alt="KK" 
                             class="img-thumbnail" 
                             style="width: 150px;">
                    </div>
                @endif
                <input type="file" name="kk" class="form-control" accept="image/*">
                @error('kk') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="ijazah_skl" class="form-label">Ijazah / SKL</label>
                @if($pengajuan->ijazah_skl)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $pengajuan->ijazah_skl) }}" 
                             alt="Ijazah/SKL" 
                             class="img-thumbnail" 
                             style="width: 150px;">
                    </div>
                @endif
                <input type="file" name="ijazah_skl" class="form-control" accept="image/*">
                @error('ijazah_skl') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="surat_kehilangan" class="form-label">Surat Kehilangan</label>
                @if($pengajuan->surat_kehilangan)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $pengajuan->surat_kehilangan) }}" 
                             alt="Surat Kehilangan" 
                             class="img-thumbnail" 
                             style="width: 150px;">
                    </div>
                @endif
                <input type="file" name="surat_kehilangan" class="form-control" accept="image/*">
                @error('surat_kehilangan') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.pengajuan-ktp.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
