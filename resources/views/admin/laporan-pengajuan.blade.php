<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengajuan Reg-On</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        .logo-container img {
            max-width: 200px;
            height: auto;
        }

        .logo-container h1 {
            color: #0077B6;
            font-size: 22px;
            margin: 0;
            font-weight: 700;
        }

        h2 {
            color: #0077B6;
            margin-top: 15px;
            margin-bottom: 5px;
            text-align: center;
        }

        .subtext {
            color: #0077B6;
            text-align: center;
            font-weight: 500;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 13px;
        }

        th, td {
            border: 1px solid #0077B6;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #0077B6;
            color: white;
        }

        tfoot th {
            background-color: #0077B6;
            color: white;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            color: #0077B6;
            font-size: 13px;
        }

        .section-title {
            color: #0077B6;
            font-size: 16px;
            margin-top: 35px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        /* 🔹 Pemisah halaman untuk PDF */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- ================= HALAMAN 1 ================= -->
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('storage/images/logo.png') }}" alt="Logo Reg-On">
        </div>
        <h2>Laporan Jumlah Pengajuan Dokumen</h2>
        @if($tanggalAwal && $tanggalAkhir)
            <p class="subtext">
                Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }}
                s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}
            </p>
        @endif
    </div>

    <!-- 🔹 Tabel ringkasan -->
    <table>
        <thead>
            <tr>
                <th>Jenis Pengajuan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Total Akun Penduduk</td><td>{{ $allUsers }}</td></tr>
            <tr><td>KTP</td><td>{{ $totalPengajuanKtp }}</td></tr>
            <tr><td>KK</td><td>{{ $totalPengajuanKk }}</td></tr>
            <tr><td>KIA</td><td>{{ $totalPengajuanKia }}</td></tr>
        </tbody>
        <tfoot>
            <tr>
                <th>Total Pengajuan Dokumen</th>
                <th>{{ $totalPengajuanDokumen }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- ================= HALAMAN 2: Rincian KTP ================= -->
    @if($dataKtp->count() > 0)
    <div class="page-break"></div>
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('storage/images/logo.png') }}" alt="Logo Reg-On">
        </div>
        <h2>Rincian Pengajuan KTP</h2>
        @if($tanggalAwal && $tanggalAkhir)
            <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }} 
            s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Pengajuan</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataKtp as $i => $ktp)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $ktp->jenis_ktp ?? '-' }}</td>
                <td>{{ $ktp->nama ?? '-' }}</td>
                <td>{{ $ktp->nik ?? '-' }}</td>
                <td>{{ ucfirst($ktp->status ?? '-') }}</td>
                <td>{{ \Carbon\Carbon::parse($ktp->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- ================= HALAMAN 3: Rincian KK ================= -->
    @if($dataKk->count() > 0)
    <div class="page-break"></div>
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('storage/images/logo.png') }}" alt="Logo Reg-On">
        </div>
        <h2>Rincian Pengajuan KK</h2>
        @if($tanggalAwal && $tanggalAkhir)
            <p class="subtext">
                Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }}
                s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Pengajuan</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataKk as $i => $kk)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $kk->jenis_kk ?? '-' }}</td>
                <td>{{ $kk->nama ?? '-' }}</td>
                <td>{{ $kk->nik ?? '-' }}</td>
                <td>{{ ucfirst($kk->status ?? '-') }}</td>
                <td>{{ \Carbon\Carbon::parse($kk->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- ================= HALAMAN 4: Rincian KIA ================= -->
    @if($dataKia->count() > 0)
    <div class="page-break"></div>
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('storage/images/logo.png') }}" alt="Logo Reg-On">
        </div>
        <h2>Rincian Pengajuan KIA</h2>
        @if($tanggalAwal && $tanggalAkhir)
            <p class="subtext">
                Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }}
                s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Pengajuan</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataKia as $i => $kia)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $kia->jenis_kia ?? '-' }}</td>
                <td>{{ $kia->nama ?? '-' }}</td>
                <td>{{ $kia->nik ?? '-' }}</td>
                <td>{{ ucfirst($kia->status ?? '-') }}</td>
                <td>{{ \Carbon\Carbon::parse($kia->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- ================= FOOTER ================= -->
    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y') }}
    </div>
</body>
</html>
