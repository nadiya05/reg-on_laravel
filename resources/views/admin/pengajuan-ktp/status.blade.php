@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">Status Pengajuan KTP</h3>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- 🔍 Form pencarian --}}
    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('pengajuan-ktp.status') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Cari NIK / Nama / Jenis KTP" style="max-width: 250px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>
    </div>

    {{-- Tabel pengajuan --}}
    <x-table>
        <x-slot name="head">
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Jenis KTP</th>
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
                <td>{{ $item->jenis_ktp }}</td>
                <td>{{ $item->tanggal_pengajuan }}</td>
                <td>
                    <a href="{{ route('resume_pengajuan.ktp', $item->id) }}" class="btn btn-sm btn-primary">Lihat Resume</a>
                </td>

                {{-- Status badge --}}
                <td class="text-center">
                    @php
                        $badgeClass = match($item->status) {
                            'selesai' => 'background-color:#28a745; color:white;',
                            'ditolak' => 'background-color:#dc3545; color:white;',
                            'sedang diproses' => 'background-color:#ffc107; color:black;',
                            default => 'background-color:#6c757d; color:white;',
                        };
                    @endphp
                    <span style="{{ $badgeClass }} padding:6px 12px; border-radius:20px; font-weight:600; font-size:0.9rem;">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>

                {{-- Update status --}}
                <td>
                    <form action="{{ route('pengajuan-ktp.status.update', $item->id) }}" method="POST" class="update-status-form">
                        @csrf
                        <select name="status" class="form-select status-select d-inline w-auto">
                            <option value="sedang diproses" {{ $item->status == 'sedang diproses' ? 'selected' : '' }}>Sedang diproses</option>
                            <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ $item->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        {{-- Notes --}}
                        <div class="notes-wrapper mt-2" style="display: {{ $item->status == 'ditolak' ? 'block' : 'none' }};">
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Tuliskan alasan penolakan">{{ $item->keterangan }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm mt-2">Ubah</button>
                    </form>
                </td>

                {{-- Aksi hapus --}}
                <td>
                    <form action="{{ route('pengajuan-ktp.status.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada data pengajuan</td>
            </tr>
            @endforelse
        </x-slot>
    </x-table>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $data->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Script toggle notes --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.update-status-form').forEach(form => {
        const select = form.querySelector('.status-select');
        const notesWrapper = form.querySelector('.notes-wrapper');

        function toggleNotes() {
            if (select.value === 'ditolak') {
                notesWrapper.style.display = 'block';
            } else {
                notesWrapper.style.display = 'none';
                const textarea = notesWrapper.querySelector('textarea');
                if (textarea) textarea.value = '';
            }
        }

        select.addEventListener('change', toggleNotes);
        toggleNotes();
    });
});
</script>
@endsection
