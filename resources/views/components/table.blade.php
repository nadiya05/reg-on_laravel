<style>
    /* Font utama */
    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Judul halaman */
    h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 100;
        color: #0077b6; /* warna biru */
    }

    /* Header tabel */
    .table thead tr {
        background-color: #0077b6 !important; /* warna background tabel header */
    }
    .table thead th {
        background-color: #0077b6 !important;
        color: #fff !important; /* warna teks */
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        text-align: center;
    }

    /* Isi tabel */
    .table tbody td {
        font-family: 'Poppins', sans-serif;
        color: #333;
    }

    /* Tombol */
    .btn {
        font-family: 'Poppins', sans-serif;
    }
    .btn-primary {
        background-color: #0077b6 !important;
        border-color: #0077b6 !important;
        font-family: 'Poppins', sans-serif;
    }
    .btn-primary:hover {
        background-color: #0077b6 !important;
        border-color: #0077b6 !important;
    }
</style>

<div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
        <thead>
            {{ $head }}
        </thead>
        <tbody>
            {{ $body }}
        </tbody>
    </table>
</div>
