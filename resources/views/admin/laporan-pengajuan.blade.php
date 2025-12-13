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

        /* ===================== KOP SURAT (FIX MIRIP WORD) ===================== */

        .kop-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 10px;
        }

        /* Logo kiri */
        .kop-wrapper .logo {
            position: absolute;
            left: 0;
            top: 0;
        }

        .kop-wrapper .logo img {
            width: 120px;
        }

        .kop-wrapper .text {
    flex: 1;               
    text-align: center;
    margin-left: 100px;   /* ruang untuk logo */
    padding-left: 30px;   /* 🔥 geser keseluruhan teks ke kanan */
}

        .judul-1 {
            font-family: "Times New Roman", serif;
            font-size: 24px;
            font-weight: 430;
            margin: 0;
        }

        .judul-2 {
            font-family: "Times New Roman", serif;
            font-size: 37px;
            font-weight: 900;
            margin: 0;
            margin-top: -5px;
        }

        .alamat {
            font-family: Arial, sans-serif;
            margin-top: 3px;
            font-size: 14px;
        }

        .garis-kop {
            border-bottom: 4px solid black;
            margin-top: 30px;   /* 🔥 TURUNKAN garis jauh dari logo */
            margin-bottom: 60px;
            width: 100%;
        }

        /* ===================== SECTION TITLE ===================== */
        h2 {
            color: #0077B6;
            margin-top: 20px;
            margin-bottom: 5px;
            text-align: center;
        }

        .subtext {
            color: #0077B6;
            text-align: center;
            font-weight: 500;
            margin-bottom: 15px;
        }

        /* ===================== TABLE ===================== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        /* ===================== FOOTER ===================== */
        .footer {
            margin-top: 40px;
            text-align: right;
            color: #0077B6;
            font-size: 13px;
        }

        /* ===================== PAGE BREAK ===================== */
        .page-break {
            page-break-before: always;
        }

    </style>
</head>
<body>

    <!-- ================= HALAMAN 1 ================= -->
    <div class="kop-wrapper">
        <div class="logo">
            <img src="{{ public_path('storage/images/logo_indramayu.png') }}" alt="Logo">
        </div>

        <div class="text">
            <div class="judul-1">PEMERINTAH KABUPATEN INDRAMAYU</div>
            <div class="judul-2">KECAMATAN LOHBENER</div>
            <div class="alamat">Jl. Raya Lohbener Telp. (0234) 276855 Lohbener – Indramayu 45252</div>
        </div>
    </div>

    <div class="garis-kop"></div>

    <h2>Laporan Jumlah Pengajuan Dokumen</h2>

    @if($tanggalAwal && $tanggalAkhir)
        <p class="subtext">
            Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }}
            s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}
        </p>
    @endif

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

    <!-- ================= HALAMAN 2: DETAIL KTP ================= -->
    @if($dataKtp->count() > 0)
    <div class="page-break"></div>

    <div class="kop-wrapper">
        <div class="logo">
            <img src="{{ public_path('storage/images/logo_indramayu.png') }}">
        </div>

        <div class="text">
            <div class="judul-1">PEMERINTAH KABUPATEN INDRAMAYU</div>
            <div class="judul-2">KECAMATAN LOHBENER</div>
            <div class="alamat">Jl. Raya Lohbener Telp. (0234) 276855 Lohbener – Indramayu 45252</div>
        </div>
    </div>

    <div class="garis-kop"></div>

    <h2>Rincian Pengajuan KTP</h2>

    @if($tanggalAwal && $tanggalAkhir)
        <p class="subtext">
            Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }}
            s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}
        </p>
    @endif

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

    <!-- ================= HALAMAN 3: DETAIL KK ================= -->
    @if($dataKk->count() > 0)
    <div class="page-break"></div>

    <div class="kop-wrapper">
        <div class="logo">
            <img src="{{ public_path('storage/images/logo_indramayu.png') }}">
        </div>

        <div class="text">
            <div class="judul-1">PEMERINTAH KABUPATEN INDRAMAYU</div>
            <div class="judul-2">KECAMATAN LOHBENER</div>
            <div class="alamat">Jl. Raya Lohbener Telp. (0234) 276855 Lohbener – Indramayu 45252</div>
        </div>
    </div>

    <div class="garis-kop"></div>

    <h2>Rincian Pengajuan KK</h2>

    @if($tanggalAwal && $tanggalAkhir)
        <p class="subtext">
            Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }}
            s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}
        </p>
    @endif

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

    <!-- ================= HALAMAN 4: DETAIL KIA ================= -->
    @if($dataKia->count() > 0)
    <div class="page-break"></div>

    <div class="kop-wrapper">
        <div class="logo">
            <img src="{{ public_path('storage/images/logo_indramayu.png') }}">
        </div>

        <div class="text">
            <div class="judul-1">PEMERINTAH KABUPATEN INDRAMAYU</div>
            <div class="judul-2">KECAMATAN LOHBENER</div>
            <div class="alamat">Jl. Raya Lohbener Telp. (0234) 276855 Lohbener – Indramayu 45252</div>
        </div>
    </div>

    <div class="garis-kop"></div>

    <h2>Rincian Pengajuan KIA</h2>

    @if($tanggalAwal && $tanggalAkhir)
        <p class="subtext">
            Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }}
            s.d. {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}
        </p>
    @endif

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

    <!-- FOOTER -->
    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y') }}
    </div>

</body>
</html>
