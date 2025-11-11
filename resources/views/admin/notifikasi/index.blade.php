@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <h3>Kelola Notifikasi</h3>
  <div class="d-flex justify-content-end mb-3">
<form method="GET" action="{{ route('admin.notifikasi') }}" class="mb-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari notifikasi..." class="form-control" style="max-width: 300px; display: inline-block;">
    <button type="submit" class="btn btn-primary">Cari</button>
</form>
  </div>

  <x-table>
    <x-slot name="head">
  <tr>
    <th>No</th>
    <th>Nama User</th>
    <th>Nama Pengaju</th>
    <th>Tipe Dokumen</th>
    <th>Jenis Dokumen</th>
    <th>Judul</th>
    <th>Pesan</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Aksi</th>
  </tr>
</x-slot>

<x-slot name="body">
  @forelse($notifikasi as $n)
  <tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $n->user->name ?? '-' }}</td>
    <td>{{ $n->nama_pengajuan ?? '-' }}</td>
    <td>{{ $n->tipe_pengajuan_label ?? '-' }}</td>
    <td>{{ $n->jenis_dokumen ?? '-' }}</td>
    <td>{{ $n->judul }}</td>
    <td>{{ $n->pesan }}</td>
    <td>{{ $n->tanggal }}</td>
    <td>
      <span class="badge bg-{{ $n->status == 'dibaca' ? 'success' : 'secondary' }}">
        {{ ucfirst(str_replace('_', ' ', $n->status)) }}
      </span>
    </td>
    <td>
      <form action="{{ route('admin.notifikasi.delete', $n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
        @csrf @method('DELETE')
        <button class="btn btn-danger btn-sm">Hapus</button>
      </form>
    </td>
  </tr>
  @empty
  <tr>
    <td colspan="10" class="text-center">Belum ada notifikasi</td>
  </tr>
  @endforelse
</x-slot>
  </x-table>
  <div class="mt-3">
    {{ $notifikasi->links('pagination::bootstrap-5') }}
</div>
</div>
@endsection
