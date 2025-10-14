@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Kelola Pengajuan KK</h4>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('pengajuan-kk.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Pengajuan
        </a>
        <form action="" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Cari..." style="max-width: 200px;">
        </form>
    </div>

    <x-table>
        <x-slot name="head">
            <tr>
                <th>ID</th>
                <th>Nomor Antrean</th>
                <th>Jenis KK</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tanggal Pengajuan</th>
                <th>Formulir Permohonan KK</th>
                <th>Surat Nikah</th>
                <th>Surat Keterangan Pindah</th>
                <th>KK Asli</th>
                <th>Surat Kematian</th>
                <th>Akta Kelahiran</th>
                <th>Ijazah</th>
                <th>Aksi</th>
            </tr>
        </x-slot>

        <x-slot name="body">
            @forelse ($pengajuan as $data)
            <tr>
                <td>{{ $data->id }}</td>
                <td>{{ $data->nomor_antrean }}</td>
                <td>{{ ucfirst($data->jenis_kk) }}</td>
                <td>{{ $data->nik }}</td>
                <td>{{ $data->nama }}</td>
                <td>{{ $data->tanggal_pengajuan }}</td>

                {{-- Formulir Permohonan KK --}}
                <td>
                    @if($data->formulir_permohonan_kk)
                        <a href="{{ asset('storage/' . $data->formulir_permohonan_kk) }}" target="_blank">Lihat Formulir</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- Surat Nikah --}}
                <td>
                    @if($data->surat_nikah)
                        <a href="{{ asset('storage/' . $data->surat_nikah) }}" target="_blank">Lihat Surat</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- Surat Keterangan Pindah --}}
                <td>
                    @if($data->surat_keterangan_pindah)
                        <a href="{{ asset('storage/' . $data->surat_keterangan_pindah) }}" target="_blank">Lihat Surat</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- KK Asli --}}
                <td>
                    @if($data->kk_asli)
                        <a href="{{ asset('storage/' . $data->kk_asli) }}" target="_blank">Lihat KK Asli</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- Surat Kematian --}}
                <td>
                    @if($data->surat_kematian)
                        <a href="{{ asset('storage/' . $data->surat_kematian) }}" target="_blank">Lihat Surat</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- Akta Kelahiran --}}
                <td>
                    @if($data->akta_kelahiran)
                        <a href="{{ asset('storage/' . $data->akta_kelahiran) }}" target="_blank">Lihat Akta</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- Ijazah --}}
                <td>
                    @if($data->ijazah)
                        <a href="{{ asset('storage/' . $data->ijazah) }}" target="_blank">Lihat Ijazah</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- Aksi --}}
                <td>
                    <a href="{{ route('pengajuan-kk.edit', $data->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('pengajuan-kk.destroy', $data->id) }}" method="POST" class="d-inline">
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
                <td colspan="14" class="text-center">Belum ada pengajuan</td>
            </tr>
            @endforelse
        </x-slot>
    </x-table>
</div>
@endsection
