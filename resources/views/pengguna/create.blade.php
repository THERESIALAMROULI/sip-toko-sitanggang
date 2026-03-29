@extends('layouts.admin')

@section('title', 'Tambah User')
@section('subtitle', 'Buat akun user baru')

@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Form User</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}" class="stack-md">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="name">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                @if ($hasUsernameColumn)
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" value="{{ old('username') }}">
                        @error('username')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="owner" @selected(old('role') === 'owner')>Owner</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="kasir" @selected(old('role', 'kasir') === 'kasir')>Kasir</option>
                    </select>
                    @error('role')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                @if ($hasStatusColumn)
                    <div class="field">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                        </select>
                        @error('status')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
            </div>

            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Simpan User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
