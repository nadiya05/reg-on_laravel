<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 100;
        color: #0077b6;
    }

    .table thead tr {
        background-color: #0077b6 !important;
    }

    .table thead th {
        background-color: #0077b6 !important;
        color: #fff !important;
        font-weight: 600;
        text-align: center;
        white-space: nowrap; /* biar header gak turun ke bawah */
    }

    .table tbody td {
        color: #333;
        white-space: nowrap; /* biar kolom gak terpotong */
    }

    .btn {
        font-family: 'Poppins', sans-serif;
    }

    .btn-primary {
        background-color: #0077b6 !important;
        border-color: #0077b6 !important;
    }

    .btn-primary:hover {
        background-color: #005f8a !important;
        border-color: #005f8a !important;
    }

    /* Biar tabel bisa discroll ke samping */
    .table-container {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Pagination styling */
    .pagination {
        justify-content: center;
        margin-top: 15px;
    }

    .page-link {
        color: #0077b6;
    }

    .page-item.active .page-link {
        background-color: #0077b6;
        border-color: #0077b6;
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
