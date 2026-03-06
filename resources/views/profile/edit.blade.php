@extends('layouts.admin')

@section('title', 'Profil')
@section('subtitle', 'Kelola informasi akun dan keamanan')

@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Informasi Profil</div>
        </div>
        <div class="card-body">
            <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                @csrf
            </form>

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
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-info">
                        Email Anda belum terverifikasi.
                        <button form="send-verification" class="btn btn-outline btn-sm" type="submit">Kirim ulang verifikasi</button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success">Link verifikasi baru sudah dikirim.</div>
                    @endif
                @endif

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
                        <input id="current_password" type="password" name="current_password" autocomplete="current-password">
                        @if ($errors->updatePassword->get('current_password'))
                            <div class="field-error">{{ $errors->updatePassword->first('current_password') }}</div>
                        @endif
                    </div>

                    <div class="field">
                        <label for="password">Password Baru</label>
                        <input id="password" type="password" name="password" autocomplete="new-password">
                        @if ($errors->updatePassword->get('password'))
                            <div class="field-error">{{ $errors->updatePassword->first('password') }}</div>
                        @endif
                    </div>

                    <div class="field field-full">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
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

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Hapus Akun</div>
        </div>
        <div class="card-body stack-md">
            <div class="alert alert-danger">
                Setelah akun dihapus, seluruh data akun akan dihapus permanen.
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}" class="stack-md" onsubmit="return confirm('Yakin ingin menghapus akun ini secara permanen?')">
                @csrf
                @method('DELETE')

                <div class="field" style="max-width:420px;">
                    <label for="delete_password">Konfirmasi Password</label>
                    <input id="delete_password" type="password" name="password" required placeholder="Masukkan password akun">
                    @if ($errors->userDeletion->get('password'))
                        <div class="field-error">{{ $errors->userDeletion->first('password') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn btn-danger">Hapus Akun Permanen</button>
            </form>
        </div>
    </div>
</div>
@endsection
