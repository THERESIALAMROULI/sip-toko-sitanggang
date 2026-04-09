{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Detail Transaksi')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Rincian transaksi dan item penjualan')

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
            <div class="card-title">Informasi Transaksi #{{ $transaction->id }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="td-actions">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="button" onclick="window.print()" class="btn btn-outline">Cetak</button>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label>Tanggal</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input type="text" value="{{ optional($transaction->transaction_date)->format('d-m-Y H:i') }}" readonly>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label>Pelanggan</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input type="text" value="{{ $transaction->customer->name ?? 'Umum / Tanpa Pelanggan' }}" readonly>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label>Metode Pembayaran</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input type="text" value="{{ strtoupper($transaction->payment_type) }}" readonly>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label>Kasir</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input type="text" value="{{ $isKasir ? 'Bony' : ($transaction->user->name ?? '-') }}" readonly>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label>Total</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input type="text" class="mono" value="Rp {{ number_format($transaction->total, 0, ',', '.') }}" readonly>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
    @if ($transaction->payment_type === 'tunai')
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-hd">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="card-title">Informasi Pembayaran Tunai</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="form-grid">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label>Uang Diterima</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input type="text" class="mono" value="Rp {{ number_format($transaction->cash_received ?? 0, 0, ',', '.') }}" readonly>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label>Kembalian</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input type="text" class="mono" value="Rp {{ number_format($transaction->change_amount ?? 0, 0, ',', '.') }}" readonly>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
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
            <div class="card-title">Detail Item</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
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
                        <th>Harga</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Qty</th>
                        {{-- Menampilkan judul kolom pada tabel. --}}
                        <th>Subtotal</th>
                    {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                    </tr>
                    {{-- Menutup bagian kepala tabel. --}}
                    </thead>
                    {{-- Membuka bagian isi tabel untuk data utama. --}}
                    <tbody>
                    {{-- Melakukan perulangan data dengan kondisi cadangan jika data kosong. --}}
                    @forelse ($transaction->details as $detail)
                        {{-- Membuka baris baru pada tabel. --}}
                        <tr>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $detail->product->name ?? '-' }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td class="mono">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td>{{ $detail->quantity }}</td>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td class="mono">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                    {{-- Menampilkan isi alternatif ketika data yang diulang tidak tersedia. --}}
                    @empty
                        {{-- Membuka baris baru pada tabel. --}}
                        <tr>
                            {{-- Menampilkan isi sel pada tabel. --}}
                            <td colspan="4" class="text-center text-muted">Tidak ada item pada transaksi ini.</td>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                    {{-- Menutup struktur forelse pada Blade. --}}
                    @endforelse
                    {{-- Menutup bagian isi tabel. --}}
                    </tbody>
                {{-- Menutup tabel setelah seluruh data selesai ditampilkan. --}}
                </table>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
    @if ($transaction->payment_type === 'utang' && $transaction->receivable)
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-hd">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="card-title">Informasi Piutang</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="form-grid">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label>Status</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input type="text" value="{{ $transaction->receivable->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}" readonly>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label>Tanggal Pelunasan</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input type="text" value="{{ optional($transaction->receivable->paid_at)->format('d-m-Y H:i') ?? '-' }}" readonly>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label>Jatuh Tempo</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input type="text" value="{{ optional($transaction->receivable->due_date)->format('d-m-Y') ?? '-' }}" readonly>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="field field-full">
                        {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                        <label>Nominal</label>
                        {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                        <input type="text" class="mono" value="Rp {{ number_format($transaction->receivable->amount, 0, ',', '.') }}" readonly>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup percabangan kondisi pada template Blade. --}}
    @endif
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection
