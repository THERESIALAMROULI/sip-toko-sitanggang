{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Data Produk')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Kelola daftar produk dan stok')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Daftar Produk</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
        @if ($products->isEmpty())
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="empty-state">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="es-icon">-</div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <p>Belum ada produk yang tersimpan.</p>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
        @else
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="tbl-wrap">
                {{-- Membuka tabel untuk menampilkan data dalam format baris dan kolom. --}}
                <table>
                    {{-- Membuka bagian kepala tabel untuk judul kolom. --}}
                    <thead>
                    {{-- Membuka baris baru pada tabel. --}}
                    <tr>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>No</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Nama Produk</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Kategori</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Harga Beli</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Harga Jual</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Stok</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Status</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Aksi</th>
                    {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                    </tr>
                    {{-- Menutup bagian kepala tabel. --}}
                    </thead>
                    {{-- Membuka bagian isi tabel untuk data utama. --}}
                    <tbody>
                    {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                    @foreach ($products as $product)
                        {{-- Membuka baris baru pada tabel. --}}
                        <tr>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $loop->iteration }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $product->nama }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $product->kategori->nama ?? '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td class="mono">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td class="mono">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>
                                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                @if ($product->stok <= 0)
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <span class="badge badge-red">Habis (0)</span>
                                {{-- Memeriksa kondisi alternatif pada tampilan. --}}
                                @elseif ($product->stok <= $product->stok_minimum)
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <span class="badge badge-amber">Rendah ({{ $product->stok }})</span>
                                {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                                @else
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <span class="badge badge-green">{{ $product->stok }} tersedia</span>
                                {{-- Menutup percabangan kondisi pada template Blade. --}}
                                @endif
                            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                            </td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>
                                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                @if ($product->aktif)
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <span class="badge badge-green">Aktif</span>
                                {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                                @else
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <span class="badge badge-gray">Nonaktif</span>
                                {{-- Menutup percabangan kondisi pada template Blade. --}}
                                @endif
                            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                            </td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>
                                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                <div class="td-actions">
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                        {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                                        @csrf
                                        {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
                                        @method('DELETE')
                                        {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                                    {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
                                    </form>
                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                </div>
                            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                            </td>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                    {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                    @endforeach
                    {{-- Menutup bagian isi tabel. --}}
                    </tbody>
                {{-- Menutup tabel setelah seluruh data selesai ditampilkan. --}}
                </table>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup percabangan kondisi pada template Blade. --}}
        @endif
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection
