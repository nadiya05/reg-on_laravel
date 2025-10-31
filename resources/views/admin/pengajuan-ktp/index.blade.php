@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Kelola Pengajuan KTP</h4>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.pengajuan-ktp.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Pengajuan
        </a>
    </div>
    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('admin.pengajuan-ktp.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Cari NIK / Nama / Jenis KTP" style="max-width: 250px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>
    </div>
    {{-- Pakai komponen table --}}
    <x-table>
        <x-slot name="head">
            <tr>
                <th>Id</th>
                <th>Nomor Antrean</th>
                <th>Jenis KTP</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tanggal Pengajuan</th>
                <th>Kartu Keluarga</th>
                <th>Surat Kehilangan</th>
                <th>Ijazah / SKL</th>
                <th>Aksi</th>
            </tr>
        </x-slot>

        <x-slot name="body">
            @forelse ($pengajuan as $data)
            <tr>
                <td>{{ $data->id }}</td>
                <td>{{ $data->nomor_antrean }}</td>
                <td>{{ $data->jenis_ktp }}</td>
                <td>{{ $data->nik }}</td>
                <td>{{ $data->nama }}</td>
                <td>{{ $data->tanggal_pengajuan }}</td>
                <td>
                    @if($data->kk)
                        <a href="{{ asset('storage/' . $data->kk) }}" target="_blank">Lihat KK</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>
                <td>
                    @if($data->surat_kehilangan)
                        <a href="{{ asset('storage/' . $data->surat_kehilangan) }}" target="_blank">Lihat Surat</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>
                <td>
                    @if($data->ijazah_skl)
                        <a href="{{ asset('storage/' . $data->ijazah_skl) }}" target="_blank">Lihat Dokumen</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.pengajuan-ktp.edit', $data->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.pengajuan-ktp.destroy', $data->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus data?')" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">Belum ada pengajuan</td>
            </tr>
            @endforelse
        </x-slot>
    </x-table>

    {{-- Baris pagination --}}
    <div>
        {{ $pengajuan->links('pagination::bootstrap-5') }}
    </div>
</div>



</div>
@endsection