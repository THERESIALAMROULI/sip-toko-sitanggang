{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Biaya Operasional')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Catat dan pantau pengeluaran operasional toko')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stack-lg">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Filter Biaya</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">+ Tambah Biaya</a>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="GET" action="{{ route('expenses.index') }}" class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="q">Pencarian</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Catatan / kategori / petugas">
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="expense_category_id">Kategori</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="expense_category_id" name="expense_category_id">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua Kategori</option>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($expenseCategories as $category)
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="{{ $category->id }}" @selected((string) ($filters['expense_category_id'] ?? '') === (string) $category->id)>
                                {{-- Menampilkan data dinamis dari server ke halaman. --}}
                                {{ $category->nama }}
                            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                            </option>
                        {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                        @endforeach
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="start_date">Dari Tanggal</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="start_date" type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="end_date">Sampai Tanggal</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="end_date" type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}">
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="td-actions field-full">
                    {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Reset</a>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
            </form>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="stat-grid">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-red">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Total Biaya</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">{{ number_format($expenses->count(), 0, ',', '.') }} data biaya</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Daftar Biaya Operasional</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($expenses->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Tidak ada data biaya pada filter ini.</p>
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
                            <th>Kategori</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Nominal</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Catatan</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Petugas</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Aksi</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($expenses as $expense)
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $loop->iteration }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ optional($expense->tanggal)->format('d-m-Y') ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $expense->category->nama ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $expense->catatan ?: '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $expense->user->name ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <div class="td-actions">
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                        {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
                                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
                                            {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                                            @csrf
                                            {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
                                            @method('DELETE')
                                            {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data biaya ini?')">Hapus</button>
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
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection
