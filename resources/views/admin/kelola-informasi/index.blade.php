@extends('layouts.admin')

@section('content')
<style>
    /* Font utama */
    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Judul halaman */
    h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 100;
        color: #0077b6; /* warna biru */
    }

    /* Header tabel */
    .table thead tr {
        background-color: #0077b6 !important;
    }
    .table thead th {
        background-color: #0077b6 !important;
        color: #fff !important;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        text-align: center;
    }

    /* Isi tabel */
    .table tbody td {
        font-family: 'Poppins', sans-serif;
        color: #333;
    }

    /* Tombol */
    .btn {
        font-family: 'Poppins', sans-serif;
    }
    .btn-primary {
        background-color: #0077b6 !important;
        border-color: #0077b6 !important;
    }
    .btn-primary:hover {
        background-color: #005f8a !important;
        border-color: #005f8a !important;
    }
</style>

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

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis Pengajuan</th>
                    <th>Jenis Dokumen</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($informasi as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['jenis_pengajuan'] }}</td>
                    <td>{{ $item['jenis_dokumen'] }}</td>
                    <td>{{ $item['deskripsi'] }}</td>
                    <td>
                        <a href="{{ route('admin.informasi.edit', $item['id']) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.informasi.destroy', $item['id']) }}" method="POST" class="d-inline">
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
            </tbody>
        </table>
    </div>
</div>
@endsection

