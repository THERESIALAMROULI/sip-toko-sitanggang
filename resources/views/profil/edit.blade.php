{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Profil')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Kelola informasi akun Anda')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stack-lg">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Informasi Profil</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="POST" action="{{ route('profile.update') }}" class="stack-md">
                {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                @csrf
                {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
                @method('PATCH')

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="form-grid">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label for="name">Nama</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                        {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                        @error('name')
                            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                            <div class="field-error">{{ $message }}</div>
                        {{-- Menutup blok tampilan error validasi. --}}
                        @enderror
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>

                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label for="username">Username</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" required autocomplete="username">
                        {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                        @error('username')
                            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                            <div class="field-error">{{ $message }}</div>
                        {{-- Menutup blok tampilan error validasi. --}}
                        @enderror
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>

                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label for="role">Role</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input id="role" type="text" value="{{ ucfirst($user->role ?? '-') }}" disabled>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="td-actions">
                    {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>

                    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                    @if (session('status') === 'profile-updated')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <span class="badge badge-green">Tersimpan</span>
                    {{-- Menutup percabangan kondisi pada template Blade. --}}
                    @endif
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
            </form>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Ubah Password</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="POST" action="{{ route('password.update') }}" class="stack-md">
                {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                @csrf
                {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
                @method('PUT')

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="form-grid">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label for="current_password">Password Saat Ini</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input id="current_password" type="password" name="current_password" autocomplete="current-password">
                        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                        @if ($errors->updatePassword->get('current_password'))
                            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                            <div class="field-error">{{ $errors->updatePassword->first('current_password') }}</div>
                        {{-- Menutup percabangan kondisi pada template Blade. --}}
                        @endif
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>

                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label for="password">Password Baru</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input id="password" type="password" name="password" autocomplete="new-password">
                        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                        @if ($errors->updatePassword->get('password'))
                            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                            <div class="field-error">{{ $errors->updatePassword->first('password') }}</div>
                        {{-- Menutup percabangan kondisi pada template Blade. --}}
                        @endif
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>

                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field field-full">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
                        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                        @if ($errors->updatePassword->get('password_confirmation'))
                            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                            <div class="field-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                        {{-- Menutup percabangan kondisi pada template Blade. --}}
                        @endif
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="td-actions">
                    {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                    <button type="submit" class="btn btn-primary">Update Password</button>

                    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                    @if (session('status') === 'password-updated')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <span class="badge badge-green">Password diperbarui</span>
                    {{-- Menutup percabangan kondisi pada template Blade. --}}
                    @endif
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
            </form>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection
