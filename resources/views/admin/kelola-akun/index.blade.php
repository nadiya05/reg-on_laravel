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
        background-color: #0077b6 !important; /* warna background tabel header */
    }
    .table thead th {
        background-color: #0077b6 !important; /* ganti dengan warna yang kamu mau */
        color: #fff !important; /* warna teks */
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
        background-color: #0077b6 !important; /* warna baru */
        border-color: #0077b6 !important;
        font-family: 'Poppins', sans-serif;
    }
    .btn-primary:hover {
        background-color: #0077b6 !important;
        border-color: #0077b6 !important;
    }

</style>

<div class="container-fluid">
    <h4 class="mb-3">Kelola Akun Pengguna</h4>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.kelola-akun.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Pengguna
        </a>
        <form action="" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Cari..." style="max-width: 200px;">
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-primary text-white">
                <tr>
                    <th>Id</th>
                    <th>Role</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jenis Kelamin</th>
                    <th>No Telepon</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->nik }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->jenis_kelamin }}</td>
                    <td>{{ $user->no_telp }}</td>
                    <td>
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}"
                                     class="rounded-circle" style="width: 80px; height: 90px; object-fit: cover;">
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.kelola-akun.edit', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.kelola-akun.destroy', $user->id) }}" method="POST" class="d-inline">
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
                    <td colspan="7">Belum ada pengguna</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
