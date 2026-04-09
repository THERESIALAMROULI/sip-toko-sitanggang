{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Transaksi')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Riwayat transaksi penjualan')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membuka blok PHP pada template Blade. --}}
@php
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    $isKasir = (auth()->user()->role ?? null) === 'kasir';
{{-- Menutup blok PHP pada template Blade. --}}
@endphp
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stack-lg">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Filter Transaksi</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="q">Cari</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="ID transaksi / nama pelanggan">
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="payment_type">Metode Pembayaran</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="payment_type" name="payment_type">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="tunai" @selected(($filters['payment_type'] ?? null) === 'tunai')>Tunai</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="utang" @selected(($filters['payment_type'] ?? null) === 'utang')>Utang</option>
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
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Reset</a>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('transactions.create') }}" class="btn btn-outline">+ Transaksi Baru</a>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
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
            <div class="card-title">Data Transaksi</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <span class="badge badge-blue">{{ $transactions->count() }} transaksi</span>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($transactions->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Belum ada transaksi pada filter ini.</p>
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
                            <th>ID</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Tanggal</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Pelanggan</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Kasir</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Pembayaran</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Total</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Status Piutang</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Aksi</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($transactions as $transaction)
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">#{{ $transaction->id }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ optional($transaction->transaction_date)->format('d-m-Y H:i') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $transaction->customer->name ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $isKasir ? 'Bony' : ($transaction->user->name ?? '-') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                    @if ($transaction->payment_type === 'tunai')
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-green">TUNAI</span>
                                    {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                                    @else
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-amber">UTANG</span>
                                    {{-- Menutup percabangan kondisi pada template Blade. --}}
                                    @endif
                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                </td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                    @if ($transaction->payment_type !== 'utang')
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-gray">Tidak ada</span>
                                    {{-- Memeriksa kondisi alternatif pada tampilan. --}}
                                    @elseif (($transaction->receivable->status ?? 'unpaid') === 'paid')
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-green">Lunas</span>
                                    {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                                    @else
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-amber">Belum lunas</span>
                                    {{-- Menutup percabangan kondisi pada template Blade. --}}
                                    @endif
                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                </td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-secondary btn-sm">Detail</a>
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
