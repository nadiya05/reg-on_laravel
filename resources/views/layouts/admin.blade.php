<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reg-On</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh; /* full layar biar bisa discroll */
            background: #f1f1f1;
            padding-top: 70px;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            overflow-y: auto; /* bikin discroll kalau panjang */
        }
        .sidebar.hidden {
            transform: translateX(-240px);
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #909090;
            text-decoration: none;
            font-weight: 100;
            font-size: 15px;
        }
        .sidebar a:hover {
            background: #d8d6d6;
            color: #909090;
        }

        /* Tombol logout samain dengan link sidebar */
.logout-link {
    display: block;
    padding: 12px 20px;
    color: #909090;
    text-decoration: none;
    font-weight: 100;
    font-size: 15px;
    width: 100%;
    text-align: left;
    background: none;
    border: none;
    cursor: pointer;
}

.logout-link:hover {
    background: #d8d6d6;
    color: #909090;
}

        /* custom scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        /* Header */
        .header {
            background: #0077B6;
            color: #fff;
            height: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%; /* selalu full */
            transition: padding-left 0.3s ease;
        }

        /* Content */
        .content {
            margin-top: 70px;
            margin-left: 240px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        .content.full {
            margin-left: 0;
        }

        /* Card statistik */
        .card-stat {
            border-radius: 12px;
            background: #0077B6;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-stat h6 { font-size: 14px; margin-bottom: 10px; }
        .card-stat h3 { font-size: 28px; font-weight: bold; }
        .card-stat i { font-size: 28px; margin-bottom: 8px; display: block; }

        /* Tombol */
        .btn-custom {
            background: #0077B6;
            color: #fff;
            border-radius: 8px;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-custom:hover {
            background: #0077B6;
            color: #fff;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-240px);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .header {
                left: 0;
                width: 100%;
            }
            .content {
                margin-left: 0;
            }
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 0; /* jarak rapat antar elemen */
        }

        .header-left img {
            height: 110px;
            transition: none; 
            width: auto;  
        }

        .menu-toggle {
            font-size: 35px;
            cursor: pointer;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar hidden" id="sidebar">
        <a href="{{ route('admin.beranda') }}">Beranda</a>
        <a href="{{ route('admin.kelola-akun') }}">Kelola Akun Pengguna</a>
        <a href="{{ route('admin.kelola-informasi') }}">Kelola Informasi</a>
        <a href="{{ route('admin.pengajuan-ktp.index') }}">Kelola Pengajuan KTP</a>
        <a href="{{ route('pengajuan-ktp.status') }}">Kelola Status KTP</a>
        <a href="{{ route('pengajuan-kk.index') }}">Kelola Pengajuan KK</a>
        <a href="{{ route('admin.pengajuan-kk.status') }}">Kelola Status KK</a>
        <a href="{{ route('pengajuan-kia.index') }}">Kelola Pengajuan KIA</a>
        <a href="{{ route('admin.pengajuan-kia.status') }}">Kelola status KIA</a>
        <a href="{{ route('admin.kelola-berita.index') }}">Kelola berita</a>
        <a href="#">Chat</a>
        <form action="{{ route('keluar') }}" method="POST">
            @csrf
            <button type="submit" class="logout-link">Keluar</button>
        </form>
</form>

</form>

    </div>

    <!-- Header -->
    <div class="header" id="header">
        <div class="header-left">
            <!-- Tombol garis tiga -->
            <span class="menu-toggle text-white" id="menu-btn">
                <i class="bi bi-list"></i>
            </span>
            <!-- Logo -->
            <img src="{{ asset('/storage/images/logo.png') }}" alt="Logo Reg-On">
        </div>
        <div class="d-flex align-items-center">
            <span class="me-3">{{ Auth::user()->name }}</span>
            @if(Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}"
                    alt="Foto Profil"
                    class="rounded-circle"
                    style="width: 40px; height: 40px; object-fit: cover; border: 2px solid white;">
            @else
                <i class="bi bi-person-circle fs-2"></i>
            @endif
        </div>
    </div>
    <!-- Content -->
    <div class="content full" id="content">
        @yield('content')
    </div>

    <script>
        const menuBtn = document.getElementById('menu-btn');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
            content.classList.toggle('full');
        });
    </script>
</body>
</html>
