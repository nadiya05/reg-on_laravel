@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <h3>Kelola Notifikasi</h3>
  <x-table>
    <x-slot name="head">
      <tr>
        <th>No</th>
        <th>Nama User</th>
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
        <td>{{ $n->user->nama ?? '-' }}</td>
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
        <td colspan="7" class="text-center">Belum ada notifikasi</td>
      </tr>
      @endforelse
    </x-slot>
  </x-table>
</div>
@endsection
