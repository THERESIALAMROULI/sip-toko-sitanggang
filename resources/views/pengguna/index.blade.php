{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Manajemen User')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Kelola akun owner, admin, dan kasir')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stack-lg">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Filter User</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="GET" action="{{ route('users.index') }}" class="search-row">
                {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                <input type="text" name="q" class="search-input" placeholder="Cari nama/email/username..." value="{{ $filters['q'] ?? '' }}">

                {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                <select name="role" class="filter-sel">
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="">Semua Role</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="owner" @selected(($filters['role'] ?? null) === 'owner')>Owner</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="admin" @selected(($filters['role'] ?? null) === 'admin')>Admin</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="kasir" @selected(($filters['role'] ?? null) === 'kasir')>Kasir</option>
                {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                </select>

                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($hasStatusColumn)
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select name="status" class="filter-sel">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua Status</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="aktif" @selected(($filters['status'] ?? null) === 'aktif')>Aktif</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="nonaktif" @selected(($filters['status'] ?? null) === 'nonaktif')>Nonaktif</option>
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                {{-- Menutup percabangan kondisi pada template Blade. --}}
                @endif

                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="submit" class="btn btn-secondary">Filter</button>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Reset</a>
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
            <div class="card-title">Daftar User</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User</a>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($users->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Belum ada user.</p>
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
                            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                            @if ($hasUsernameColumn)
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Username</th>
                            {{-- Menutup percabangan kondisi pada template Blade. --}}
                            @endif
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Email</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Role</th>
                            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                            @if ($hasStatusColumn)
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Status</th>
                            {{-- Menutup percabangan kondisi pada template Blade. --}}
                            @endif
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Aksi</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($users as $user)
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $loop->iteration }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $user->name }}</td>
                                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                @if ($hasUsernameColumn)
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $user->username ?: '-' }}</td>
                                {{-- Menutup percabangan kondisi pada template Blade. --}}
                                @endif
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $user->email }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td><span class="badge badge-blue">{{ strtoupper($user->role) }}</span></td>
                                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                @if ($hasStatusColumn)
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>
                                        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                        @if (($user->status ?? 'aktif') === 'aktif')
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
                                {{-- Menutup percabangan kondisi pada template Blade. --}}
                                @endif
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <div class="td-actions">
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary btn-sm">Edit</a>

                                        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                        @if ($hasStatusColumn)
                                            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
                                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST">
                                                {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                                                @csrf
                                                {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
                                                @method('PATCH')
                                                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                                                <button type="submit" class="btn btn-secondary btn-sm">
                                                    {{-- Menampilkan data dinamis dari server ke halaman. --}}
                                                    {{ ($user->status ?? 'aktif') === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                                </button>
                                            {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
                                            </form>
                                        {{-- Menutup percabangan kondisi pada template Blade. --}}
                                        @endif

                                        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                        @if ((int) $user->id !== (int) auth()->id())
                                            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                                                @csrf
                                                {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
                                                @method('DELETE')
                                                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">Hapus</button>
                                            {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
                                            </form>
                                        {{-- Menutup percabangan kondisi pada template Blade. --}}
                                        @endif
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
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection
