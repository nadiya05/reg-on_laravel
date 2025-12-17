@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Kelola Pengajuan KK</h4>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('pengajuan-kk.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Pengajuan
        </a>
    </div>

    {{-- Form Pencarian --}}
    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('pengajuan-kk.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2"
                placeholder="Cari NIK / Nama / Jenis KK / Nomor Antrean" style="max-width: 280px;" autocomplete="off">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>
        </form>
    </div>

    @php
        // mapping jenis KK → dokumen yang relevan
        $kkDocs = [
            'pendidikan' => ['ijazah'],
            'status_perkawinan' => ['surat_nikah'],
            'perceraian' => ['akta_cerai'],
            'kematian' => ['surat_kematian'],
            'gol_darah' => ['bukti_cek_darah'],
            'penambahan_anggota' => ['akta_kelahiran'],
            'pindahan' => ['surat_keterangan_pindah'],
            'pisah_kk' => ['surat_pisah_kk'],
        ];

        // semua label dokumen untuk header
        $docLabels = [
            'formulir_permohonan_kk' => 'Formulir KK',
            'surat_nikah' => 'Surat Nikah',
            'surat_keterangan_pindah' => 'Surat Keterangan Pindah',
            'kk_asli' => 'KK Asli',
            'surat_kematian' => 'Surat Kematian',
            'akta_kelahiran' => 'Akta Kelahiran',
            'ijazah' => 'Ijazah',
            'akta_cerai' => 'Akta Cerai',
            'bukti_cek_darah' => 'Bukti Cek Darah',
            'surat_penggabungan_kk' => 'Surat Penggabungan KK',
            'surat_pisah_kk' => 'Surat Pisah KK',
        ];
    @endphp

    <x-table>
        <x-slot name="head">
            <tr>
                <th>ID</th>
                <th>Nomor Antrean</th>
                <th>Jenis KK</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tanggal Pengajuan</th>
                @foreach($docLabels as $label)
                    <th>{{ $label }}</th>
                @endforeach
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

                @foreach($docLabels as $field => $label)
                    <td>
                        @php
                            $relevant = ($field === 'formulir_permohonan_kk') || 
                                        (isset($kkDocs[$data->jenis_kk]) && in_array($field, $kkDocs[$data->jenis_kk]));
                        @endphp

                        @if($relevant)
                            @if($data->$field)
                                <a href="{{ asset('storage/' . $data->$field) }}" target="_blank">Lihat</a>
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                @endforeach

                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('pengajuan-kk.edit', $data->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('pengajuan-kk.destroy', $data->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus data?')" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ 6 + count($docLabels) + 1 }}" class="text-center">Belum ada pengajuan</td>
            </tr>
            @endforelse
        </x-slot>
    </x-table>

    <div class="mt-3">
        {{ $pengajuan->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
