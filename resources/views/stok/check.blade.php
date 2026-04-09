{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Cek Stok Produk')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Pantau status ketersediaan produk')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stack-lg">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Filter Stok</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="GET" action="{{ route('stocks.check') }}" class="search-row">
                {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                <input type="text" name="q" class="search-input" placeholder="Cari nama produk..." value="{{ $filters['q'] ?? '' }}">

                {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                <select name="kategori_id" class="filter-sel">
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="">Semua Kategori</option>
                    {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                    @foreach ($kategoris as $kategori)
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="{{ $kategori->id }}" @selected((int) ($filters['kategori_id'] ?? 0) === $kategori->id)>{{ $kategori->nama }}</option>
                    {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                    @endforeach
                {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                </select>

                {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                <select name="status" class="filter-sel">
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="">Semua Status</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="normal" @selected(($filters['status'] ?? null) === 'normal')>Normal</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="low" @selected(($filters['status'] ?? null) === 'low')>Hampir Habis</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="out" @selected(($filters['status'] ?? null) === 'out')>Habis</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="inactive" @selected(($filters['status'] ?? null) === 'inactive')>Nonaktif</option>
                {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                </select>

                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="submit" class="btn btn-secondary">Filter</button>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('stocks.check') }}" class="btn btn-secondary">Reset</a>
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
            <div class="card-title">Daftar Stok Produk</div>
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
                    <p>Tidak ada data stok pada filter ini.</p>
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
                            <th>Produk</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Kategori</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Stok</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Stok Minimum</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Status</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($products as $product)
                            {{-- Membuka blok PHP pada template Blade. --}}
                            @php
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                $status = 'Normal';
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                $badge = 'badge-green';

                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                if (! $product->aktif) {
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    $status = 'Nonaktif';
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    $badge = 'badge-gray';
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                } elseif ((int) $product->stok <= 0) {
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    $status = 'Habis';
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    $badge = 'badge-red';
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                } elseif ((int) $product->stok <= (int) $product->stok_minimum) {
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    $status = 'Hampir Habis';
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    $badge = 'badge-amber';
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                }
                            {{-- Menutup blok PHP pada template Blade. --}}
                            @endphp
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $loop->iteration }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $product->nama }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $product->kategori->nama ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">{{ $product->stok }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">{{ $product->stok_minimum }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
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
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection
