@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Kelola Berita</h4>

    {{-- 🔍 Tombol tambah dan search --}}
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.kelola-berita.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Berita
        </a>

        <form action="{{ route('admin.kelola-berita.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="form-control me-2" placeholder="Cari judul berita..." 
                   style="max-width: 250px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>
    </div>

    {{-- 🔹 Tabel data berita --}}
    <x-table>
        <x-slot name="head">
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Foto</th>
                <th>Konten</th>
                <th>Aksi</th>
            </tr>
        </x-slot>

        <x-slot name="body">
            @forelse($berita as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>
                    @if($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" 
                             alt="Foto Berita" 
                             style="width: 80px; height: 60px; object-fit: cover;">
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>
                <td>{{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 100, '...') }}</td>
                <td>
                    <a href="{{ route('admin.kelola-berita.edit', $item->id) }}" 
                       class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.kelola-berita.destroy', $item->id) }}" 
                          method="POST" class="d-inline" 
                          onsubmit="return confirm('Yakin hapus berita ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data berita</td>
            </tr>
            @endforelse
        </x-slot>
    </x-table>

    {{-- 🔢 Pagination --}}
    <div class="mt-3">
        {{ $berita->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
