<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Landing Page Admin Lohbener</title>

    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    />

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: #f4f7ff;
            margin: 0;
            padding: 0;
        }

        /* ===================== HEADER DENGAN WAVE SMOOTH ===================== */
        .header-section {
            background-color: #0077b6;
            padding: 90px 20px 200px;
            position: relative;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            overflow: visible;
        }

        .header-inner {
            max-width: 1200px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        .header-left {
            width: 260px;
        }

        .header-text h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .btn-login {
            background: white;
            color: #0077b6;
            border-radius: 20px;
            font-weight: 600;
            padding: 10px 22px;
            text-decoration: none;
            display: inline-block;
        }

        .header-right {
            width: 340px;
        }

        .header-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            height: 200px;
            pointer-events: none;
        }

        .header-wave svg {
            position: relative;
            display: block;
            width: 100%;
            height: 200px;
        }

        @media (max-width: 768px) {
            .header-inner {
                flex-direction: column;
                text-align: center;
            }

            .header-left,
            .header-right {
                width: 230px;
                margin-top: 20px;
            }
        }

        /* ===================== CONTENT LAIN ===================== */
        .section-title {
            font-size: 22px;
            font-weight: 600;
            color: #0077b6;
            margin-top: 30px;
            text-align: center;
        }

        /* ===================== BLUE BOX BARU ===================== */
        .blue-box {
            background: #0077b6; /* biru tua */
            color: #ffffff; /* tulisan putih */
            padding: 20px;
            border-radius: 20px;
            font-size: 15px;
            font-weight: 500;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .white-box {
            background: #ffffff;
            padding: 18px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            font-size: 14px;
        }

        .admin-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 25px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #777;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- ===================== HEADER ===================== -->
    <div class="header-section">
        <div class="header-inner">
            <img src="{{ asset('storage/images/landing page2.jpeg') }}" alt="Left Person" class="header-left" />

            <div class="header-text">
                <h2>Selamat Datang Di Website<br />Kecamatan Lohbener Admin!!</h2>
                <a href="{{ route('masuk') }}" class="btn btn-login">ketuk untuk masuk</a>
            </div>

            <img src="{{ asset('storage/images/landing page1.jpeg') }}" alt="Landing Page" class="header-right" />
        </div>

        <!-- WAVE SMOOTH -->
        <div class="header-wave">
            <svg
                viewBox="0 0 1440 320"
                preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    fill="#ffffff"
                    fill-opacity="1"
                    d="M0,256 C320,400 480,0 720,192 C960,384 1120,64 1440,224 L1440,320 L0,320 Z"
                ></path>
            </svg>
        </div>
    </div>

    <!-- ===================== KONTEN ===================== -->
    <p class="section-title">Kecamatan Lohbener melayani dokumen apa saja sih?</p>

    <div class="container">
        <div class="row text-center mb-3">
            <div class="col-md-6 mb-3">
                <div class="blue-box">Informasi seputar pengajuan dokumen</div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="blue-box">melayani pembuatan dokumen KTP</div>
            </div>
        </div>

        <div class="row text-center mb-4">
            <div class="col-md-6 mb-3">
                <div class="blue-box">melayani pembuatan dokumen KK</div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="blue-box">melayani pembuatan dokumen KK</div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <div style="background:#0077b6; padding:30px 25px; border-radius:30px; color:white; text-align:center; box-shadow:0 4px 12px rgba(0,0,0,0.15); font-size:15px; line-height:1.6;">
                    <p style="margin-bottom:18px; font-weight:500;">
                        Kunjungi Kantor Kecamatan Lohbener untuk mendapatkan informasi lebih lengkap mengenai layanan dan proses administrasi yang tersedia. Kami siap membantu kebutuhan Anda.
                    </p>

                    <div style="background:white; padding:20px; border-radius:20px; color:#0077B6; display:inline-block; text-align:left;">
                        <div style="display:flex; align-items:flex-start; gap:12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                            </svg>              
                            <p style="margin:0; font-weight:600; font-size:15px;">
                                Raya No. 8, Jalan Lohbener – Jatibarang Lama, Lohbener,  
                                Indramayu, Kabupaten Indramayu,  
                                Jawa Barat 45252, Indonesia
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <p class="section-title" style="margin-top:40px;">tugas admin ngapain aja sih?</p>

        <div class="container mt-4">
            <div class="row justify-content-center">

                <div class="col-md-4 d-flex flex-column align-items-center">
                    <img src="{{ asset('storage/images/bundaran mangga.jpeg') }}" class="admin-img">
                    <img src="{{ asset('storage/images/bundaran mangga.jpeg') }}" class="admin-img">
                    <img src="{{ asset('storage/images/bundaran mangga.jpeg') }}" class="admin-img">
                </div>

                <div class="col-md-7">
                    <div style="background:#0077B6; color:white; padding:30px; border-radius:15px; font-size:16px; line-height:1.7; box-shadow:0 6px 15px rgba(0,0,0,0.15);">
                        <p style="font-size:18px; font-weight:600;">
                            Admin Kecamatan memiliki peran penting dalam memastikan informasi dan layanan dapat diakses oleh masyarakat secara mudah, cepat, dan akurat. Beberapa tugas utama admin antara lain:
                        </p>

                        <ol style="padding-left:20px; margin-top:20px;">
                            <li style="margin-bottom:15px;"><b>Mengelola Informasi</b><br>Memperbarui data, berita, dan pengumuman agar warga mendapat informasi terbaru.</li>
                            <li style="margin-bottom:15px;"><b>Mengatur Layanan Online</b><br>Mengelola pengajuan surat, KTP, KK, dan layanan administrasi lainnya.</li>
                            <li style="margin-bottom:15px;"><b>Memeriksa & Memvalidasi Data</b><br>Meninjau data pengajuan warga agar lengkap dan sesuai.</li>
                            <li style="margin-bottom:15px;"><b>Menindaklanjuti Pengajuan</b><br>Memantau status layanan dan memberikan notifikasi kepada warga.</li>
                            <li><b>Menjaga Sistem Tetap Aman</b><br>Memastikan website tetap rapi, aman, dan mudah digunakan。</li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer">© 2025 Kecamatan Lohbener</div>
</body>
</html>
