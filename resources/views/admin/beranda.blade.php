@extends('layouts.admin')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    body { font-family: 'Poppins', sans-serif; }

    .dashboard-header { position: relative; margin-bottom: 20px; }
    .dashboard-title { font-weight: 600; color: #0077B6; }
    .dashboard-subtitle { color: #0077B6; }

    .dashboard-date {
        position: absolute; top: 0; right: 0;
        display: flex; align-items: center; gap: 8px;
        font-size: 14px; background: #0077B6; color: #fff;
        padding: 8px 14px; border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        font-weight: 500;
    }
    .dashboard-date i { font-size: 18px; }

    .card-stat {
        border-radius: 15px; background: #0077B6; color: #fff;
        padding: 20px 15px; text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }
    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }
    .card-stat i {
        font-size: 30px; margin-bottom: 10px; display: block;
    }
    .card-stat h6 { font-size: 14px; font-weight: 500; margin-bottom: 6px; }
    .card-stat h3 { font-size: 22px; font-weight: 700; margin: 0; }

    .btn-custom {
        display: inline-block;
        background: #0077B6;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s ease;
    }
    .btn-custom:hover {
        background: #005f8f;
        color: #fff;
    }

    a .card-stat { transition: transform 0.25s ease, box-shadow 0.25s ease; }
    a .card-stat:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
</style>

<div class="dashboard-header">
    <h4 class="dashboard-title">Selamat Datang, {{ Auth::user()->name }}!</h4>
    <p class="dashboard-subtitle">Administrator Panel - Reg-On</p>

    <div class="dashboard-date" id="tanggalHariIni">
        <i class="bi bi-calendar-event"></i>
        <span></span>
    </div>
</div>

<div class="row">
    <!-- 🔹 Kiri: Grafik + Filter + Logo -->
    <div class="col-md-5 mb-4">
        <div class="card p-3 shadow-sm" style="border-radius: 15px; min-height: 470px;">
            <!-- 🔍 Filter Tanggal -->
            <!-- 🔍 Filter Rentang Tanggal -->
            <form method="GET" action="{{ route('admin.beranda') }}" class="mb-3 d-flex gap-2 flex-wrap">
                <div>
                    <label class="form-label mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_awal" class="form-control" value="{{ $tanggalAwal ?? '' }}">
                </div>
                <div>
                    <label class="form-label mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir ?? '' }}">
                </div>
                <div class="align-self-end">
                    <button class="btn btn-primary">Tampilkan</button>
                </div>
            </form>
            <!-- 📊 Grafik -->
            <div style="height: 300px;">
                <canvas id="grafikPengajuan"></canvas>
            </div>

            <!-- 📄 Tombol Download PDF -->
            <div class="text-center mt-3">
                <a href="{{ route('admin.beranda.pdf', ['tanggal_awal' => $tanggalAwal ?? '', 'tanggal_akhir' => $tanggalAkhir ?? '']) }}" 
                class="btn-custom" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>
            </div>
        </div>
    </div>

    <!-- 🔹 Kanan: Statistik + Total Semua Pengajuan -->
    <div class="col-md-7">
        <div class="row g-3">
            <div class="col-md-6 col-sm-12">
                <a href="{{ route('admin.kelola-akun') }}" class="text-decoration-none text-white">
                    <div class="card-stat">
                        <i class="bi bi-people-fill"></i>
                        <h6>Total Akun Penduduk</h6>
                        <h3>{{ $allUsers ?? 0}}</h3>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-sm-12">
                <a href="{{ route('admin.pengajuan-ktp.index') }}" class="text-decoration-none text-white">
                    <div class="card-stat">
                        <i class="bi bi-person-vcard"></i>
                        <h6>Total Pengajuan KTP</h6>
                        <h3>{{ $totalPengajuanKtp ?? 0}}</h3>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-sm-12">
                <a href="{{ route('pengajuan-kk.index') }}" class="text-decoration-none text-white">
                    <div class="card-stat">
                        <i class="bi bi-journal-text"></i>
                        <h6>Total Pengajuan KK</h6>
                        <h3>{{ $totalPengajuanKk ?? 0}}</h3>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-sm-12">
                <a href="{{ route('pengajuan-kia.index') }}" class="text-decoration-none text-white">
                    <div class="card-stat">
                        <i class="bi bi-file-earmark-person"></i>
                        <h6>Total Pengajuan KIA</h6>
                        <h3>{{ $totalPengajuanKia ?? 0}}</h3>
                    </div>
                </a>
            </div>
        </div>

        <!-- 🧮 Kanan Bawah: Total Semua Pengajuan -->
        <div class="card mt-4 p-3 shadow-sm text-end" style="border-radius: 15px;">
            <h6 style="color:#0077B6; font-weight:600;">Total Semua Pengajuan</h6>
            <h3 style="font-weight:700; color:#023e8a;">
                {{ $totalPengajuanKtp + $totalPengajuanKk + $totalPengajuanKia ?? 0}}
            </h3>
            <div class="d-flex align-items-center mt-4" style="gap: 10px;">
                <small style="color:#555;">Registrasi antrean online untuk Pelayanan Dokumen Kependudukan</small>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikPengajuan').getContext('2d');

    const gradientKtp = ctx.createLinearGradient(0, 0, 0, 300);
    gradientKtp.addColorStop(0, '#007bff');
    gradientKtp.addColorStop(1, '#66b2ff');

    const gradientKk = ctx.createLinearGradient(0, 0, 0, 300);
    gradientKk.addColorStop(0, '#28a745');
    gradientKk.addColorStop(1, '#7cd68a');

    const gradientKia = ctx.createLinearGradient(0, 0, 0, 300);
    gradientKia.addColorStop(0, '#ffc107');
    gradientKia.addColorStop(1, '#ffe38a');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['KTP', 'KK', 'KIA'],
            datasets: [{
                label: 'Jumlah Pengajuan',
                data: [{{ $totalPengajuanKtp }}, {{ $totalPengajuanKk }}, {{ $totalPengajuanKia }}],
                backgroundColor: [gradientKtp, gradientKk, gradientKia],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => ` ${context.parsed.y} Pengajuan`
                    }
                }
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    const today = new Date();
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    document.querySelector('#tanggalHariIni span').textContent =
        today.toLocaleDateString('id-ID', options);
</script>
@endsection