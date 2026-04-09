{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Tambah Pelanggan')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Masukkan data pelanggan baru')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Form Pelanggan</div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
        <form action="{{ route('customers.store') }}" method="POST" class="stack-md">
            {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
            @csrf

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="name">Nama</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
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
                    <label for="phone">Telepon</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('phone')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field field-full">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="address">Alamat</label>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <textarea id="address" name="address" placeholder="Alamat pelanggan (opsional)">{{ old('address') }}</textarea>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('address')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="td-actions">
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="submit" class="btn btn-primary">Simpan Pelanggan</button>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
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
