@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Tambah Pengguna Baru</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.kelola-akun.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- NIK --}}
            <div class="mb-3 user-only">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" name="nik" id="nik" class="form-control" value="{{ old('nik') }}">
                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Nama --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div class="mb-3 user-only">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="Pria" {{ old('jenis_kelamin') == 'Pria' ? 'selected' : '' }}>Pria</option>
                    <option value="Wanita" {{ old('jenis_kelamin') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                </select>
                @error('jenis_kelamin') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- No Telepon --}}
            <div class="mb-3 user-only">
                <label for="no_telp" class="form-label">No Telepon</label>
                <input type="text" name="no_telp" id="no_telp" class="form-control" value="{{ old('no_telp') }}">
                @error('no_telp') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Role --}}
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Sandi</label>
                <input type="password" name="password" id="password" class="form-control" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Tombol --}}
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.kelola-akun') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

{{-- SCRIPT UNTUK SEMBUNYIIN FIELD --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const userOnlyFields = document.querySelectorAll('.user-only');

        function toggleUserFields() {
            if (roleSelect.value === 'admin') {
                userOnlyFields.forEach(field => field.style.display = 'none');
                // hilangkan required agar tidak error
                userOnlyFields.forEach(field => {
                    const inputs = field.querySelectorAll('input, select');
                    inputs.forEach(input => input.removeAttribute('required'));
                });
            } else {
                userOnlyFields.forEach(field => field.style.display = '');
                userOnlyFields.forEach(field => {
                    const inputs = field.querySelectorAll('input, select');
                    inputs.forEach(input => input.setAttribute('required', true));
                });
            }
        }

        // Jalankan saat halaman load
        toggleUserFields();

        // Jalankan setiap kali role diganti
        roleSelect.addEventListener('change', toggleUserFields);
    });
</script>
@endsection
