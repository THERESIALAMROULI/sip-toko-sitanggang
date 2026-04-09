{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Dashboard')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Ringkasan aktivitas toko hari ini')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membuka blok PHP pada template Blade. --}}
@php
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    $role = auth()->user()->role;
{{-- Menutup blok PHP pada template Blade. --}}
@endphp

{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stat-grid">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="stat-card sc-green">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-label">Penjualan Hari Ini</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-value mono">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-sub">{{ $todayTransactionCount }} transaksi hari ini</div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="stat-card sc-blue">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-label">Total Produk</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-sub">Produk aktif tersimpan</div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="stat-card sc-amber">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-label">Total Pelanggan</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-value">{{ number_format($totalCustomers, 0, ',', '.') }}</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-sub">Data customer terdaftar</div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="stat-card sc-purple">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-label">Piutang Berjalan</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-value mono">Rp {{ number_format($outstandingReceivables, 0, ',', '.') }}</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sc-sub">{{ $overdueReceivablesCount }} piutang lewat jatuh tempo</div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>

{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="grid-2 mb-4">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Akses Cepat</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <span class="badge badge-blue">{{ now()->translatedFormat('d M Y') }}</span>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="quick-actions">
                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($role === 'admin')
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('users.index') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">U</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Manajemen User</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Kelola akun sistem</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('kategoris.index') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">K</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Kategori</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Master kategori produk</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('products.index') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">P</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Data Produk</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Kelola item jual</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('customers.index') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">C</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Pelanggan</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Master customer</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('expense_categories.index') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">B</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Kategori Biaya</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Klasifikasi pengeluaran</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('expenses.index') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">O</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Biaya Operasional</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Catat pengeluaran toko</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                {{-- Menutup percabangan kondisi pada template Blade. --}}
                @endif

                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($role === 'kasir')
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('transactions.create') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">T</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Transaksi Baru</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Input penjualan</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('receivables.index') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">R</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Piutang</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Pantau kredit</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('stocks.check') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">C</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Cek Stok</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Pantau ketersediaan</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                {{-- Menutup percabangan kondisi pada template Blade. --}}
                @endif

                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if (in_array($role, ['admin', 'owner'], true))
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('reports.sales') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">L</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Laporan Penjualan</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Analisis penjualan</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('reports.receivables') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">U</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Laporan Utang</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Rekap piutang</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('reports.stock') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">S</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Laporan Stok</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Mutasi & persediaan</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('reports.expenses') }}" class="quick-card">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-icon">B</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-label">Laporan Pengeluaran</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="qc-sub">Total biaya & laba/rugi</div>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </a>
                {{-- Menutup percabangan kondisi pada template Blade. --}}
                @endif

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('profile.edit') }}" class="quick-card">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="qc-icon">A</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="qc-label">Profil</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="qc-sub">Ubah akun</div>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Perbandingan Bulan Ini</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body stack-md">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="sc-label">Penjualan Bulan Ini</div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="sc-value mono">Rp {{ number_format($thisMonthSales, 0, ',', '.') }}</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="sc-label">Penjualan Bulan Lalu</div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="sc-value mono">Rp {{ number_format($lastMonthSales, 0, ',', '.') }}</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="alert {{ $salesGrowthPercent >= 0 ? 'alert-success' : 'alert-danger' }} mb-0">
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                Tren:
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <strong>
                    {{-- Menampilkan data dinamis dari server ke halaman. --}}
                    {{ $salesGrowthPercent >= 0 ? '+' : '' }}{{ number_format($salesGrowthPercent, 1, ',', '.') }}%
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </strong>
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                dibanding bulan lalu.
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>

{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="grid-2 mb-4">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Transaksi Terbaru</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($latestTransactions->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Belum ada transaksi terbaru.</p>
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
                            <th>Tanggal</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Pelanggan</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Pembayaran</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Total</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($latestTransactions as $transaction)
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ optional($transaction->transaction_date)->format('d-m-Y H:i') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $transaction->customer->name ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <span class="badge {{ $transaction->payment_type === 'utang' ? 'badge-amber' : 'badge-blue' }}">
                                        {{-- Menampilkan data dinamis dari server ke halaman. --}}
                                        {{ strtoupper($transaction->payment_type) }}
                                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                    </span>
                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                </td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
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

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Produk Stok Menipis</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($lowStockProducts->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Tidak ada produk dengan stok kritis.</p>
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
                            <th>Produk</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Stok</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Status</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($lowStockProducts as $product)
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $product->nama }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">{{ $product->stok }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                    @if ($product->stok <= 0)
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-red">Habis</span>
                                    {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                                    @else
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-amber">Rendah</span>
                                    {{-- Menutup percabangan kondisi pada template Blade. --}}
                                    @endif
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

{{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
@if ($role === 'owner')
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="alert {{ $outOfStockCount > 0 ? 'alert-danger' : 'alert-success' }} mb-4">
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        Produk habis saat ini: <strong>{{ $outOfStockCount }}</strong>.
        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
        @if ($outOfStockCount > 0)
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            Segera lakukan restok agar penjualan tidak terganggu.
        {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
        @else
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            Semua produk masih tersedia.
        {{-- Menutup percabangan kondisi pada template Blade. --}}
        @endif
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="grid-2 mb-4">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-hd">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="card-title">Top 5 Produk Terlaris (12 Bulan)</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($ownerTopProducts->isEmpty())
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="empty-state">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="es-icon">-</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <p>Belum ada data produk terjual.</p>
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
                                <th>Produk</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Qty</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Total</th>
                            {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                            </tr>
                            {{-- Menutup bagian kepala tabel. --}}
                            </thead>
                            {{-- Membuka bagian isi tabel untuk data utama. --}}
                            <tbody>
                            {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                            @foreach ($ownerTopProducts as $item)
                                {{-- Membuka baris baru pada tabel. --}}
                                <tr>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $item->product_name }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td class="mono">{{ number_format($item->qty_sold, 0, ',', '.') }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td class="mono">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
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

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-hd">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="card-title">Top 5 Utang Terlama</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($ownerOldestReceivables->isEmpty())
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="empty-state">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="es-icon">-</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <p>Tidak ada utang aktif.</p>
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
                                <th>Pelanggan</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Transaksi</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Lama</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Jumlah</th>
                            {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                            </tr>
                            {{-- Menutup bagian kepala tabel. --}}
                            </thead>
                            {{-- Membuka bagian isi tabel untuk data utama. --}}
                            <tbody>
                            {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                            @foreach ($ownerOldestReceivables as $item)
                                {{-- Membuka baris baru pada tabel. --}}
                                <tr>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $item->transaction->customer->name ?? '-' }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>#{{ $item->transaction_id }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $item->created_at ? $item->created_at->diffInDays(now()) : 0 }} hari</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td class="mono">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
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

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="grid-2 mb-4">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-hd">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="card-title">Tren Penjualan 12 Bulan</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <canvas id="ownerSalesTrendChart" height="110"></canvas>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-hd">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="card-title">Grafik Penjualan vs Piutang</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <canvas id="ownerSalesVsReceivablesChart" height="110"></canvas>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup percabangan kondisi pada template Blade. --}}
@endif

{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Piutang Lewat Jatuh Tempo</div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <span class="badge badge-red">
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            Rp {{ number_format($overdueReceivablesAmount, 0, ',', '.') }}
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </span>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
        @if ($overdueReceivables->isEmpty())
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="empty-state">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="es-icon">-</div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <p>Tidak ada piutang yang melewati jatuh tempo.</p>
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
                        <th>Customer</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Transaksi</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Tanggal Utang</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Jumlah</th>
                    {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                    </tr>
                    {{-- Menutup bagian kepala tabel. --}}
                    </thead>
                    {{-- Membuka bagian isi tabel untuk data utama. --}}
                    <tbody>
                    {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                    @foreach ($overdueReceivables as $receivable)
                        {{-- Membuka baris baru pada tabel. --}}
                        <tr>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $receivable->transaction->customer->name ?? '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>#{{ $receivable->transaction_id }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ optional($receivable->created_at)->format('d-m-Y') ?? '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td class="mono">Rp {{ number_format($receivable->amount, 0, ',', '.') }}</td>
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

{{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
@if ($role === 'owner')
    {{-- Menambahkan konten ke stack tertentu pada layout. --}}
    @push('scripts')
    {{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
    <script>
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        (() => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const labels = {!! json_encode($ownerTrendLabels) !!};
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const salesValues = {!! json_encode($ownerSalesTrendValues) !!};
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const receivableValues = {!! json_encode($ownerReceivableTrendValues) !!};

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const salesTrendCanvas = document.getElementById('ownerSalesTrendChart');
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (salesTrendCanvas) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                new Chart(salesTrendCanvas.getContext('2d'), {
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    type: 'line',
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    data: {
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        labels: labels,
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        datasets: [{
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            label: 'Penjualan',
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            data: salesValues,
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            borderColor: 'rgba(28, 77, 141, 1)',
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            backgroundColor: 'rgba(73, 136, 196, 0.2)',
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            tension: 0.35,
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            fill: true
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        }]
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    },
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    options: {
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        responsive: true,
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        scales: {
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            y: {
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                beginAtZero: true,
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                ticks: {
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    callback: (value) => 'Rp ' + Number(value).toLocaleString('id-ID')
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                }
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            }
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        }
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    }
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                });
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const compareCanvas = document.getElementById('ownerSalesVsReceivablesChart');
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (compareCanvas) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                new Chart(compareCanvas.getContext('2d'), {
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    type: 'bar',
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    data: {
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        labels: labels,
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        datasets: [{
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            label: 'Penjualan',
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            data: salesValues,
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            backgroundColor: 'rgba(28, 77, 141, 0.75)',
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            borderRadius: 6
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        }, {
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            label: 'Piutang',
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            data: receivableValues,
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            backgroundColor: 'rgba(73, 136, 196, 0.75)',
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            borderRadius: 6
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        }]
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    },
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    options: {
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        responsive: true,
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        scales: {
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            y: {
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                beginAtZero: true,
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                ticks: {
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    callback: (value) => 'Rp ' + Number(value).toLocaleString('id-ID')
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                }
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            }
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        }
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    }
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                });
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        })();
    {{-- Menutup blok JavaScript pada halaman ini. --}}
    </script>
    {{-- Menutup blok push pada template Blade. --}}
    @endpush
{{-- Menutup percabangan kondisi pada template Blade. --}}
@endif
