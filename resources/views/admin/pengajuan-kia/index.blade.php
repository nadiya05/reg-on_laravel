@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Kelola Pengajuan KIA</h4>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('pengajuan-kia.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Pengajuan
        </a>
        <form action="" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Cari..." style="max-width: 200px;">
        </form>
    </div>

    {{-- Pakai komponen table --}}
    <x-table>
        <x-slot name="head">
            <tr>
                <th>ID</th>
                <th>Nomor Antrean</th>
                <th>Jenis KIA</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tanggal Pengajuan</th>
                <th>Kartu Keluarga</th>
                <th>Akta Lahir</th>
                <th>Surat Nikah</th>
                <th>KTP Ortu</th>
                <th>Pass Foto</th>
                <th>Aksi</th>
            </tr>
        </x-slot>

        <x-slot name="body">
            @forelse ($pengajuan as $data)
            <tr>
                <td>{{ $data->id }}</td>
                <td>{{ $data->nomor_antrean }}</td>
                <td>{{ ucfirst($data->jenis_kia) }}</td>
                <td>{{ $data->nik }}</td>
                <td>{{ $data->nama }}</td>
                <td>{{ $data->tanggal_pengajuan }}</td>

                {{-- KK --}}
                <td>
                    @if($data->kk)
                        <a href="{{ asset('storage/' . $data->kk) }}" target="_blank">Lihat KK</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- Akta Lahir --}}
                <td>
                    @if($data->akta_lahir)
                        <a href="{{ asset('storage/' . $data->akta_lahir) }}" target="_blank">Lihat Akta</a>
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

                {{-- KTP Ortu --}}
                <td>
                    @if($data->ktp_ortu)
                        <a href="{{ asset('storage/' . $data->ktp_ortu) }}" target="_blank">Lihat KTP Ortu</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- Pass Foto --}}
                <td>
                    @if($data->pass_foto)
                        <a href="{{ asset('storage/' . $data->pass_foto) }}" target="_blank">Lihat Foto</a>
                    @else
                        <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                {{-- Aksi --}}
                <td>
                    <a href="{{ route('pengajuan-kia.edit', $data->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('pengajuan-kia.destroy', $data->id) }}" method="POST" class="d-inline">
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
                <td colspan="12" class="text-center">Belum ada pengajuan</td>
            </tr>
            @endforelse
        </x-slot>
    </x-table>
</div>
@endsection
