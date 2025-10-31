@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Kelola Akun Pengguna</h4>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.kelola-akun.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Pengguna
        </a>
    </div>
<div class="d-flex justify-content-end mb-3">
    <form action="{{ route('admin.kelola-akun') }}" method="GET" class="d-flex">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control me-2" placeholder="Cari Nama / NIK / Email / Role"
               style="max-width: 250px;">
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
                <th>Role</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Jenis Kelamin</th>
                <th>No Telepon</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </x-slot>

        <x-slot name="body">
            @forelse ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->nik ?? '-' }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->jenis_kelamin ?? '-' }}</td>
                <td>{{ $user->no_telp ?? '-' }}</td>
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
                <td colspan="9">Belum ada pengguna</td>
            </tr>
            @endforelse
        </x-slot>
    </x-table>
       {{-- Baris pagination --}}
    <div>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
</div>
@endsection
