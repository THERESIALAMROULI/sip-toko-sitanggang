@extends('layouts.admin')
@section('title', 'Profil')
@section('subtitle', 'Akun Anda')
@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Informasi Profil</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" class="stack-md">
                @csrf
                @method('PATCH')
                <div class="form-grid">
                    <div class="field">
                        <label for="name">Nama</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" required autocomplete="username">
                        @error('username')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="role">Peran</label>
                        <input id="role" type="text" value="{{ ucfirst($user->role ?? '-') }}" disabled>
                    </div>
                </div>
                <div class="td-actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    @if (session('status') === 'profile-updated')
                        <span class="badge badge-green">Tersimpan</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Ubah Password</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}" class="stack-md">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="field">
                        <label for="current_password">Password Saat Ini</label>
                        <input id="current_password" type="password" name="current_password" autocomplete="current-password" required>
                        @if ($errors->updatePassword->get('current_password'))
                            <div class="field-error">{{ $errors->updatePassword->first('current_password') }}</div>
                        @endif
                    </div>
                    <div class="field">
                        <label for="password">Password Baru</label>
                        <input id="password" type="password" name="password" autocomplete="new-password" required>
                        @if ($errors->updatePassword->get('password'))
                            <div class="field-error">{{ $errors->updatePassword->first('password') }}</div>
                        @endif
                    </div>
                    <div class="field field-full">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
                        @if ($errors->updatePassword->get('password_confirmation'))
                            <div class="field-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                        @endif
                    </div>
                </div>
                <div class="td-actions">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                    @if (session('status') === 'password-updated')
                        <span class="badge badge-green">Password diperbarui</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
