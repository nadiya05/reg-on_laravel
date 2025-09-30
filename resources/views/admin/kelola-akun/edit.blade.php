@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Pengguna</h4>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.kelola-akun.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', $user->nik) }}" required>
                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="Pria" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Pria' ? 'selected' : '' }}>Pria</option>
                    <option value="Wanita" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                </select>
                @error('jenis_kelamin') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="no_telp" class="form-label">No Telepon</label>
                <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $user->no_telp) }}" required>
                @error('no_telp') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Sandi</label>
                <input type="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="Kosongkan jika tidak ingin ganti">
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Foto --}}
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                @if($user->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $user->foto) }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control">
                @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.kelola-akun') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
