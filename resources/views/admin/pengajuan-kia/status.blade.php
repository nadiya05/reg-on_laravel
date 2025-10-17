@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4 ">Daftar Pengajuan KIA</h3>

    {{-- ✅ Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ✅ Tabel Pengajuan KIA --}}
    <x-table>
        <x-slot name="head">
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Jenis KIA</th>
                <th>Tanggal Pengajuan</th>
                <th>Resume</th>
                <th>Status</th>
                <th>Update Status</th>
                <th>Aksi</th>
            </tr>
        </x-slot>

        <x-slot name="body">
            @foreach($data as $item)
            <tr>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->jenis_kia }}</td>
                <td>{{ $item->tanggal_pengajuan }}</td>

                {{-- 🔹 Tombol Lihat Resume --}}
                <td>
                    <a href="{{ route('resume_pengajuan.kia', $item->id) }}">
                        <button class="btn btn-sm btn-primary">Lihat Resume</button>
                    </a>
                </td>

                {{-- 🔹 Badge status (fix warna sesuai status) --}}
                <td class="text-center">
                    @php
                        $status = strtolower(trim($item->status)); // normalize biar ga sensitif huruf besar/kecil
                        $badgeClass = match($status) {
                            'selesai' => 'background-color: #28a745; color: white;', // hijau
                            'ditolak' => 'background-color: #dc3545; color: white;', // merah
                            'sedang diproses' => 'background-color: #ffc107; color: black;', // kuning
                            default => 'background-color: #6c757d; color: white;', // abu
                        };
                    @endphp
                    <span style="{{ $badgeClass }} padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>

                {{-- 🔹 Form update status --}}
                <td>
                    <form action="{{ route('admin.pengajuan-kia.status.update', $item->id) }}" method="POST">
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
                    <form action="{{ route('admin.pengajuan-kia.status.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </x-slot>
    </x-table>
</div>
@endsection
