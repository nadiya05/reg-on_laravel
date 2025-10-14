<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resume Pendaftaran Antrean KTP</title>
    <style>
        /* Font Poppins */
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 400;
            src: url("{{ public_path('fonts/Poppins/Poppins-Regular.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'Poppins';
            font-style: bold;
            font-weight: 700;
            src: url("{{ public_path('fonts/Poppins/Poppins-Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .resume-card {
            max-width: 700px;
            margin: 30px auto;
            padding: 40px;
            border-radius: 15px;
            background-color: #f0f8ff;
            border: 3px solid #0077B6;
            position: relative;
            box-sizing: border-box;
        }

        .resume-card h2 {
            color: #0077B6;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .resume-card p {
            font-size: 16px;
            margin: 8px 0;
        }

        .highlight {
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
            max-width: 120px;
        }

        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="resume-card">
        {{-- Logo kanan atas --}}
        <img src="{{ public_path('storage/images/logo.png') }}" alt="Logo" class="logo">

        <h2>Resume Pendaftaran Antrean KTP</h2>

        <p><span class="highlight">Nomor Antrian:</span> {{ $pengajuan->nomor_antrean }}</p>
        <p><span class="highlight">NIK:</span> {{ $pengajuan->nik }}</p>
        <p><span class="highlight">Nama:</span> {{ $pengajuan->nama }}</p>
        <p><span class="highlight">Email:</span> {{ $user->email }}</p>
        <p><span class="highlight">No Telepon:</span> {{ $user->no_telp }}</p>
        <p><span class="highlight">Jenis KTP:</span> {{ $pengajuan->jenis_ktp }}</p>
        <p><span class="highlight">Tanggal Pengajuan:</span> {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y') }}</p>

        @if($pengajuan->jenis_ktp == 'Pemula')
            <p class="footer-note">
                Datang ke kecamatan 30 menit setelah mencetak nomor antrean <br>
                (untuk pengajuan KTP pemula)
            </p>
        @endif
    </div>
</body>
</html>
