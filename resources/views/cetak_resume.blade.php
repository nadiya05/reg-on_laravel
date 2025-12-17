<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resume Pendaftaran Antrean</title>
    <style>
        @font-face {
            font-family: 'Poppins';
            font-weight: 400;
            src: url("{{ public_path('fonts/Poppins/Poppins-Regular.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'Poppins';
            font-weight: 700;
            src: url("{{ public_path('fonts/Poppins/Poppins-Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .resume-card {
            max-width: 700px;
            margin: 30px auto;
            padding: 40px;
            border-radius: 15px;
            background-color: #f0f8ff;
            border: 3px solid #0077B6;
            position: relative;
        }

        h2 {
            color: #0077B6;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }

        p {
            font-size: 16px;
            margin: 8px 0;
        }

        .label {
            font-weight: 700;
            color: #0077B6;
        }

        .footer-note {
            margin-top: 25px;
            text-align: center;
            font-style: italic;
            color: #023E8A;
        }

        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            max-width: 110px;
        }

        @page {
            size: A4 portrait;
            margin: 20mm;
        }
    </style>
</head>
<body>
<div class="resume-card">

    <img src="{{ public_path('storage/images/logo.png') }}" class="logo" alt="Logo">

    {{-- ================= JUDUL DINAMIS ================= --}}
    <h2>
        Resume Pendaftaran Antrean
        {{ $tipePengajuan }}
    </h2>

    <p><span class="label">Nomor Antrean:</span> {{ $pengajuan->nomor_antrean }}</p>
    <p><span class="label">NIK:</span> {{ $pengajuan->nik }}</p>
    <p><span class="label">Nama:</span> {{ $pengajuan->nama }}</p>
    <p><span class="label">Email:</span> {{ $user->email }}</p>
    <p><span class="label">No. Telepon:</span> {{ $user->no_telp }}</p>

    {{-- ================= JENIS PENGAJUAN ================= --}}
    @if($tipePengajuan === 'KTP')
        <p><span class="label">Jenis KTP:</span> {{ $pengajuan->jenis_ktp }}</p>
    @elseif($tipePengajuan === 'KIA')
        <p><span class="label">Jenis KIA:</span> {{ $pengajuan->jenis_kia }}</p>
    @elseif($tipePengajuan === 'KK')
        <p><span class="label">Jenis KK:</span> {{ $pengajuan->jenis_kk }}</p>
    @endif

    <p>
        <span class="label">Tanggal Pengajuan:</span>
        {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y') }}
    </p>

    {{-- ================= CATATAN KHUSUS ================= --}}
    @if($tipePengajuan === 'KTP' && $pengajuan->jenis_ktp === 'Pemula')
        <p class="footer-note">
            Datang ke kecamatan ±30 menit setelah mencetak nomor antrean
            (khusus pengajuan KTP pemula).
        </p>
    @endif

    @if($tipePengajuan === 'KIA')
        <p class="footer-note">
            Pastikan membawa dokumen asli saat verifikasi di kecamatan.
        </p>
    @endif

    @if($tipePengajuan === 'KK')
        <p class="footer-note">
            Proses KK dilakukan sesuai jenis permohonan yang diajukan.
        </p>
    @endif

</div>
</body>
</html>
