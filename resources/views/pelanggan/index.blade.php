{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Data Pelanggan')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Kelola data pelanggan toko')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Daftar Pelanggan</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Tambah Pelanggan</a>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
        @if ($customers->isEmpty())
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="empty-state">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="es-icon">-</div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <p>Belum ada data pelanggan.</p>
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
                        <th>Nama</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Telepon</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Alamat</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Aksi</th>
                    {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                    </tr>
                    {{-- Menutup bagian kepala tabel. --}}
                    </thead>
                    {{-- Membuka bagian isi tabel untuk data utama. --}}
                    <tbody>
                    {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                    @foreach ($customers as $customer)
                        {{-- Membuka baris baru pada tabel. --}}
                        <tr>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $loop->iteration }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $customer->name }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $customer->phone }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $customer->address ?: '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>
                                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                <div class="td-actions">
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                        {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                                        @csrf
                                        {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
                                        @method('DELETE')
                                        {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data pelanggan?')">Hapus</button>
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
