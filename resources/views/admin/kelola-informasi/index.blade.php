@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Kelola Informasi</h4>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.kelola-informasi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Informasi
        </a>
        <form action="" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Cari..." style="max-width: 200px;">
        </form>
    </div>

    <x-table>
    <x-slot name="head">
        <tr>
            <th>ID</th>
            <th>Jenis Pengajuan</th>
            <th>Jenis Dokumen</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </x-slot>

    <x-slot name="body">
        @forelse($informasi as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->jenis_pengajuan }}</td>
            <td>{{ $item->jenis_dokumen }}</td>
            <td>{{ $item->deskripsi }}</td>
            <td>
                <a href="{{ route('admin.kelola-informasi.edit', $item->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.kelola-informasi.destroy', $item->id) }}" method="POST" class="d-inline">
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
            <td colspan="5">Belum ada data informasi</td>
        </tr>
        @endforelse
    </x-slot>
</x-table>
</div>
@endsection
