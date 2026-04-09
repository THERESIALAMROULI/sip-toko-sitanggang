{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Riwayat Stok')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Riwayat penambahan stok produk')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Data Stok Histories</div>
        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
        @if ((Auth::user()->role ?? null) === 'admin')
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <a href="{{ route('stok_histories.create') }}" class="btn btn-primary">+ Tambah Stok</a>
        {{-- Menutup percabangan kondisi pada template Blade. --}}
        @endif
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
        <form method="GET" action="{{ route('stok_histories.index') }}" class="search-row">
            {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
            <select name="produk_id" class="filter-sel">
                {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                <option value="">Semua Produk</option>
                {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                @foreach ($products as $product)
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="{{ $product->id }}" @selected((int) ($filters['produk_id'] ?? 0) === $product->id)>{{ $product->nama }}</option>
                {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                @endforeach
            {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
            </select>

            {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
            <select name="supplier_id" class="filter-sel">
                {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                <option value="">Semua Supplier</option>
                {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                @foreach ($suppliers as $supplier)
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="{{ $supplier->id }}" @selected((int) ($filters['supplier_id'] ?? 0) === $supplier->id)>{{ $supplier->nama }}</option>
                {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                @endforeach
            {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
            </select>

            {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
            <input type="date" name="start_date" class="search-input" value="{{ $filters['start_date'] ?? '' }}">
            {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
            <input type="date" name="end_date" class="search-input" value="{{ $filters['end_date'] ?? '' }}">

            {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
            <button type="submit" class="btn btn-secondary">Filter</button>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <a href="{{ route('stok_histories.index') }}" class="btn btn-secondary">Reset</a>
        {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
        </form>

        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
        @if ($stokHistories->isEmpty())
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="empty-state">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="es-icon">-</div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <p>Belum ada riwayat stok.</p>
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
                        <th>Tanggal</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Produk</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Supplier</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Mutasi</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Stok Sebelum</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Stok Sesudah</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Petugas</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Keterangan</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Aksi</th>
                    {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                    </tr>
                    {{-- Menutup bagian kepala tabel. --}}
                    </thead>
                    {{-- Membuka bagian isi tabel untuk data utama. --}}
                    <tbody>
                    {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                    @foreach ($stokHistories as $history)
                        {{-- Membuka baris baru pada tabel. --}}
                        <tr>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $loop->iteration }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ optional($history->tanggal)->format('d/m/Y H:i') ?? '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $history->produk->nama ?? '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $history->supplier->nama ?? '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td class="mono">{{ $history->jumlah > 0 ? '+' : '' }}{{ number_format($history->jumlah, 0, ',', '.') }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $history->stok_sebelum }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $history->stok_sesudah }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $history->user->name ?? '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $history->keterangan ?: '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>
                                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                <div class="td-actions">
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <a href="{{ route('stok_histories.edit', $history->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
                                    <form action="{{ route('stok_histories.destroy', $history->id) }}" method="POST">
                                        {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                                        @csrf
                                        {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
                                        @method('DELETE')
                                        {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus mutasi stok ini?')">Hapus</button>
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
