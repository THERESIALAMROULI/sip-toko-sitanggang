@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('subtitle', 'Edit pengguna')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Edit Pengguna</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="stack-md">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field">
                    <label for="name">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                @if ($hasUsernameColumn)
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" required>
                        <div class="form-hint">Dipakai saat masuk.</div>
                        @error('username')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="field">
                    <label for="role">Peran</label>
                    <select id="role" name="role" required>
                        <option value="owner" @selected(old('role', $user->role) === 'owner')>Owner</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                        <option value="kasir" @selected(old('role', $user->role) === 'kasir')>Kasir</option>
                    </select>
                    @error('role')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                @if ($hasStatusColumn)
                    <div class="field">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="aktif" @selected(old('status', $user->status ?? 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $user->status ?? 'aktif') === 'nonaktif')>Nonaktif</option>
                        </select>
                        @error('status')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="field">
                    <label for="password">Password baru (opsional)</label>
                    <input id="password" type="password" name="password">
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Konfirmasi password baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation">
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
