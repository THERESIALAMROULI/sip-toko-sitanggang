{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Edit User')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Perbarui data user')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Form Edit User</div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="stack-md">
            {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
            @csrf
            {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
            @method('PUT')

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="name">Nama</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
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
                    <label for="email">Email</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('email')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($hasUsernameColumn)
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label for="username">Username</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}">
                        {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                        @error('username')
                            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                            <div class="field-error">{{ $message }}</div>
                        {{-- Menutup blok tampilan error validasi. --}}
                        @enderror
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                {{-- Menutup percabangan kondisi pada template Blade. --}}
                @endif

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="role">Role</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="role" name="role" required>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="owner" @selected(old('role', $user->role) === 'owner')>Owner</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="kasir" @selected(old('role', $user->role) === 'kasir')>Kasir</option>
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('role')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($hasStatusColumn)
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label for="status">Status</label>
                        {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                        <select id="status" name="status" required>
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="aktif" @selected(old('status', $user->status ?? 'aktif') === 'aktif')>Aktif</option>
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="nonaktif" @selected(old('status', $user->status ?? 'aktif') === 'nonaktif')>Nonaktif</option>
                        {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                        </select>
                        {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                        @error('status')
                            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                            <div class="field-error">{{ $message }}</div>
                        {{-- Menutup blok tampilan error validasi. --}}
                        @enderror
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                {{-- Menutup percabangan kondisi pada template Blade. --}}
                @endif

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="password">Password Baru (opsional)</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="password" type="password" name="password">
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('password')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="password_confirmation" type="password" name="password_confirmation">
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="td-actions">
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="submit" class="btn btn-primary">Update User</button>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
        </form>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection
