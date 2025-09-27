@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Tambah Pengguna</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.kelola-akun.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Pria" {{ old('jenis_kelamin') == 'Pria' ? 'selected' : '' }}>Pria</option>
                    <option value="Wanita" {{ old('jenis_kelamin') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                </select>
                @error('jenis_kelamin') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="no_telp" class="form-label">No Telepon</label>
                <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp') }}" required>
                @error('no_telp') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Sandi</label>
                <input type="password" name="password" class="form-control" value="{{ old('password') }}" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.kelola-akun') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
