{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Edit Produk')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Perbarui data produk')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Form Edit Produk</div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
        <form method="POST" action="{{ route('products.update', $product->id) }}" class="stack-md">
            {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
            @csrf
            {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
            @method('PUT')

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field field-full">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="nama">Nama Produk</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="nama" type="text" name="nama" value="{{ old('nama', $product->nama) }}" required>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('nama')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="kategori_id">Kategori</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="kategori_id" name="kategori_id" required>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($kategoris as $kategori)
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="{{ $kategori->id }}" @selected((int) old('kategori_id', $product->kategori_id) === $kategori->id)>{{ $kategori->nama }}</option>
                        {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                        @endforeach
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('kategori_id')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="harga_beli">Harga Beli</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="harga_beli" type="number" name="harga_beli" value="{{ old('harga_beli', $product->harga_beli) }}" min="0" required>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('harga_beli')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="harga_jual">Harga Jual</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="harga_jual" type="number" name="harga_jual" value="{{ old('harga_jual', $product->harga_jual) }}" min="0" required>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('harga_jual')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="stok">Stok</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="stok" type="number" name="stok" value="{{ old('stok', $product->stok) }}" min="0" required>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('stok')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="stok_minimum">Stok Minimum</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="stok_minimum" type="number" name="stok_minimum" value="{{ old('stok_minimum', $product->stok_minimum) }}" min="0" required>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('stok_minimum')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field field-full">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="aktif">Status Produk</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="aktif" name="aktif" required>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="1" @selected((string) old('aktif', $product->aktif ? '1' : '0') === '1')>Aktif</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="0" @selected((string) old('aktif', $product->aktif ? '1' : '0') === '0')>Nonaktif</option>
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('aktif')
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
                <button type="submit" class="btn btn-primary">Update Produk</button>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
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
