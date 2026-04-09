{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Edit Mutasi Stok')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Perbarui data mutasi stok produk')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membuka blok PHP pada template Blade. --}}
@php
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    $defaultJenis = old('jenis', $stokHistory->jumlah < 0 ? 'koreksi_kurang' : 'masuk');
{{-- Menutup blok PHP pada template Blade. --}}
@endphp

{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Form Edit Mutasi Stok</div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
        <form method="POST" action="{{ route('stok_histories.update', $stokHistory->id) }}" class="stack-md">
            {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
            @csrf
            {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
            @method('PUT')

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="jenis">Jenis Mutasi</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="jenis" name="jenis" required>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="masuk" @selected($defaultJenis === 'masuk')>Barang Masuk</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="koreksi_tambah" @selected($defaultJenis === 'koreksi_tambah')>Koreksi Tambah</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="koreksi_kurang" @selected($defaultJenis === 'koreksi_kurang')>Koreksi Kurang</option>
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('jenis')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="produk_id">Produk</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="produk_id" name="produk_id" required>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Pilih produk</option>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($products as $product)
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="{{ $product->id }}" @selected((int) old('produk_id', $stokHistory->produk_id) === $product->id)>
                                {{-- Menampilkan data dinamis dari server ke halaman. --}}
                                {{ $product->nama }} (stok: {{ $product->stok }})
                            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                            </option>
                        {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                        @endforeach
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('produk_id')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="supplier_id">Supplier</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="supplier_id" name="supplier_id">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Tanpa supplier</option>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($suppliers as $supplier)
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="{{ $supplier->id }}" @selected((int) old('supplier_id', $stokHistory->supplier_id ?? 0) === $supplier->id)>{{ $supplier->nama }}</option>
                        {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                        @endforeach
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('supplier_id')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="jumlah">Jumlah</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="jumlah" type="number" name="jumlah" value="{{ old('jumlah', abs((int) $stokHistory->jumlah)) }}" min="1" required>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('jumlah')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field field-full">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="keterangan">Keterangan</label>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <textarea id="keterangan" name="keterangan">{{ old('keterangan', $stokHistory->keterangan) }}</textarea>
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('keterangan')
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
                <button type="submit" class="btn btn-primary">Update Mutasi Stok</button>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('stok_histories.index') }}" class="btn btn-secondary">Kembali</a>
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
