@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Kelola Berita</h4>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.kelola-berita.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Berita
        </a>
        <form action="" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Cari judul..." style="max-width: 200px;">
        </form>
    </div>

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
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Berita" style="width: 80px; height: 60px; object-fit: cover;">
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>
                <td>
                    {{-- tampilkan ringkas isi konten (misalnya 100 karakter) --}}
                    {{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 100, '...') }}
                </td>
                <td>
                    <a href="{{ route('admin.kelola-berita.edit', $item->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.kelola-berita.destroy', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus berita ini?')" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">Belum ada data berita</td>
            </tr>
            @endforelse
        </x-slot>
    </x-table>
</div>
@endsection
