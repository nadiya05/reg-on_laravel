<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 1000px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            min-height: 650px; /* tambahin tinggi card */

        }
       .login-image {
            background: url("{{ asset('storage/images/bundaran mangga.jpeg') }}") no-repeat center center;
            background-size: cover;
            min-height: 650px; /* tinggi gambar disejajarin dengan form */
        }
        .login-form {
            padding: 0px 40px 150px; /* atas 20px, samping 40px, bawah 50px */
        }
        .form-control {
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
        }
        .btn-login {
            border-radius: 8px;
            background-color: #0077B6;
            color: white;
            font-weight: 600;
        }
        .btn-login:hover {
            background-color: #0077B6;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }
        .logo img {
            height: 200px;
            margin-right: 10px;
        }
        .logo span {
            font-size: 24px;
            font-weight: 600;
        }
        h4 {
            color:#0077B6
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="login-card row g-0">
            <!-- Bagian Gambar Kiri -->
            <div class="col-md-6 login-image"></div>

            <!-- Bagian Form Kanan -->
            <div class="col-md-6 d-flex align-items-center">
                <div class="login-form w-100">
                    <!-- Logo -->
                    <div class="logo">
                        <img src="{{ asset('storage/images/logo.png') }}" alt="Logo">
                    </div>

                    <h4 class="mb-4 text-center">MASUK ADMIN</h4>

                    <form method="POST" action="{{ route('masuk') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-login">Masuk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
