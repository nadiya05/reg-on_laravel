@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Status Pengajuan KK</h3>

    {{-- ✅ Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 🔍 Form Pencarian --}}
    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('admin.pengajuan-kk.status') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2"
                placeholder="Cari NIK / Nama / Jenis KK" style="max-width: 280px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>
    </div>

    {{-- ✅ Tabel Pengajuan KK --}}
    <x-table>
        <x-slot name="head">
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Jenis KK</th>
                <th>Tanggal Pengajuan</th>
                <th>Resume</th>
                <th>Status</th>
                <th>Update Status</th>
                <th>Aksi</th>
            </tr>
        </x-slot>

        <x-slot name="body">
            @forelse($data as $item)
            <tr>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->jenis_kk }}</td>
                <td>{{ $item->tanggal_pengajuan }}</td>

                {{-- 🔹 Tombol Lihat Resume --}}
                <td>
                    <a href="{{ route('resume_pengajuan.kk', $item->id) }}" class="btn btn-sm btn-primary">Lihat Resume</a>
                </td>

                {{-- 🔹 Badge status --}}
                <td class="text-center">
                    @php
                        $status = strtolower(trim($item->status));
                        $badgeClass = match($status) {
                            'selesai' => 'background-color: #28a745; color: white;',
                            'ditolak' => 'background-color: #dc3545; color: white;',
                            'sedang diproses' => 'background-color: #ffc107; color: black;',
                            default => 'background-color: #6c757d; color: white;',
                        };
                    @endphp
                    <span style="{{ $badgeClass }} padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>

                {{-- 🔹 Form update status --}}
                <td>
                    <form action="{{ route('admin.pengajuan-kk.status.update', $item->id) }}" method="POST">
                        @csrf
                        <select name="status" class="form-select d-inline w-auto">
                            <option value="sedang diproses" {{ strtolower($item->status) == 'sedang diproses' ? 'selected' : '' }}>Sedang diproses</option>
                            <option value="selesai" {{ strtolower($item->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ strtolower($item->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Ubah</button>
                    </form>
                </td>

                {{-- 🔹 Tombol hapus --}}
                <td>
                    <form action="{{ route('admin.pengajuan-kk.status.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada pengajuan</td>
            </tr>
            @endforelse
        </x-slot>
    </x-table>

    {{-- 🔢 Baris pagination --}}
    <div>
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
