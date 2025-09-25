@extends('layouts.admin')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-header {
        position: relative;
        margin-bottom: 20px;
    }

    .dashboard-title {
        font-weight: 600;
        color: #0077B6;
    }
    .dashboard-subtitle {
        color: #0077B6;
    }

    /* Box tanggal */
    .dashboard-date {
        position: absolute;
        top: 0;
        right: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        background: #0077B6;
        color: #fff;
        padding: 8px 14px;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        font-weight: 500;
    }
    .dashboard-date i {
        font-size: 18px;
    }

    /* Card Statistik */
    .card-stat {
        border-radius: 15px;
        background: #0077B6;
        color: #fff;
        padding: 20px 15px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }
    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }
    .card-stat i {
        font-size: 30px;
        margin-bottom: 10px;
        display: block;
    }
    .card-stat h6 {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 6px;
    }
    .card-stat h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    /* Tombol */
    .btn-custom {
        display: inline-block;
        background: #fff;
        color: #0077B6;
        border: 2px solid #0077B6;
        border-radius: 10px;
        padding: 8px 18px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s ease;
        margin-top: 40px;
    }
    .btn-custom:hover {
        background: #0077B6;
        color: #fff;
    }
</style>

<div class="dashboard-header">
    <h4 class="dashboard-title">Selamat Datang, Admin!</h4>
    <p class="dashboard-subtitle">Administrator Panel - Reg-On</p>

    <!-- Kalender -->
    <div class="dashboard-date" id="tanggalHariIni">
        <i class="bi bi-calendar-event"></i>
        <span></span>
    </div>
</div>

<div class="row">
    <!-- Kiri: Grafik + tombol -->
    <div class="col-md-5 mb-4">
        <div class="card p-3 shadow-sm" style="border-radius: 15px; height: 280px;">
            <canvas id="grafikPengajuan"></canvas>
            <div class="text-center">
                <a href="#" class="btn-custom">Download PDF</a>
            </div>
        </div>
    </div>

    <!-- Kanan: Statistik -->
    <div class="col-md-7">
        <div class="row g-3">
            <div class="col-md-6 col-sm-12">
                <div class="card-stat">
                    <i class="bi bi-people-fill"></i>
                    <h6>Total Penduduk</h6>
                    <h3>{{ $data['totalPenduduk'] }}</h3>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="card-stat">
                    <i class="bi bi-person-vcard"></i>
                    <h6>Total Pengajuan KTP</h6>
                    <h3>{{ $data['totalKTP'] }}</h3>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="card-stat">
                    <i class="bi bi-journal-text"></i>
                    <h6>Total Pengajuan KK</h6>
                    <h3>{{ $data['totalKK'] }}</h3>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="card-stat">
                    <i class="bi bi-file-earmark-person"></i>
                    <h6>Total Pengajuan KIA</h6>
                    <h3>{{ $data['totalKIA'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikPengajuan').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['KTP', 'KK', 'KIA'],
            datasets: [{
                label: 'Jumlah Pengajuan',
                data: [{{ $data['totalKTP'] }}, {{ $data['totalKK'] }}, {{ $data['totalKIA'] }}],
                backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Tanggal hari ini
    const today = new Date();
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    document.querySelector('#tanggalHariIni span').textContent =
        today.toLocaleDateString('id-ID', options);
</script>
@endsection
