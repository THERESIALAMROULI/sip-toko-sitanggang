{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Kategori Biaya')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Kelola kategori untuk pencatatan biaya operasional')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Daftar Kategori Biaya</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <a href="{{ route('expense_categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
        @if ($expenseCategories->isEmpty())
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="empty-state">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="es-icon">-</div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <p>Belum ada kategori biaya.</p>
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
                        <th>Nama Kategori</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Deskripsi</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Status</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Dipakai</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Aksi</th>
                    {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                    </tr>
                    {{-- Menutup bagian kepala tabel. --}}
                    </thead>
                    {{-- Membuka bagian isi tabel untuk data utama. --}}
                    <tbody>
                    {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                    @foreach ($expenseCategories as $expenseCategory)
                        {{-- Membuka baris baru pada tabel. --}}
                        <tr>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $loop->iteration }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $expenseCategory->nama }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $expenseCategory->deskripsi ?: '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>
                                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                @if ($expenseCategory->aktif)
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
                            <td>{{ number_format($expenseCategory->expenses_count, 0, ',', '.') }} biaya</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>
                                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                <div class="td-actions">
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <a href="{{ route('expense_categories.edit', $expenseCategory->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
                                    <form action="{{ route('expense_categories.destroy', $expenseCategory->id) }}" method="POST">
                                        {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                                        @csrf
                                        {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
                                        @method('DELETE')
                                        {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori biaya ini?')">Hapus</button>
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
