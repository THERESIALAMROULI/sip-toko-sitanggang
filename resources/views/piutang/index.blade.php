{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Piutang')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Daftar piutang dari transaksi kredit')

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
            <div class="card-title">Filter Piutang</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="GET" action="{{ route('receivables.index') }}" class="form-grid">
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
                    <label for="status">Status</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="status" name="status">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="unpaid" @selected(($filters['status'] ?? null) === 'unpaid')>Belum Lunas</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="paid" @selected(($filters['status'] ?? null) === 'paid')>Lunas</option>
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="customer_id">Pelanggan</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="customer_id" name="customer_id">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua Pelanggan</option>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($customers as $customer)
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="{{ $customer->id }}" @selected((string) ($filters['customer_id'] ?? '') === (string) $customer->id)>
                                {{-- Menampilkan data dinamis dari server ke halaman. --}}
                                {{ $customer->name }}
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
                    <label for="overdue_only">Lewat Jatuh Tempo</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="overdue_only" name="overdue_only">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="1" @selected(($filters['overdue_only'] ?? null) === '1')>Hanya yang lewat tempo</option>
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="td-actions field-full">
                    {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('receivables.index') }}" class="btn btn-secondary">Reset</a>
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
        <div class="stat-card sc-amber">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Total Belum Lunas</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($summaryUnpaidAmount, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Dari hasil filter aktif</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-red">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Lewat Jatuh Tempo</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($summaryOverdueAmount, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Belum lunas dan melewati tanggal jatuh tempo</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-blue">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Piutang Lunas</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value">{{ number_format($summaryPaidCount, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Jumlah dokumen piutang berstatus lunas</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Daftar Piutang</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <span class="badge badge-blue">{{ $receivables->count() }} data</span>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <p class="form-hint mb-3">
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                Piutang terbentuk otomatis dari transaksi dengan metode pembayaran kredit.
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </p>

            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($receivables->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Tidak ada piutang pada filter ini.</p>
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
                            <th>Transaksi</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Customer</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Jumlah</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Status</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Jatuh Tempo</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Tanggal Pelunasan</th>
                            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                            @if (! $isKasir)
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Umur</th>
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
                        @foreach ($receivables as $item)
                            {{-- Membuka blok PHP pada template Blade. --}}
                            @php
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                $isOverdue = $item->status === 'unpaid' && $item->due_date && $item->due_date->lt(now());
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                $ageDays = $item->created_at ? $item->created_at->diffInDays(now()) : null;
                            {{-- Menutup blok PHP pada template Blade. --}}
                            @endphp
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $loop->iteration }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">#{{ $item->transaction_id }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $item->transaction->customer->name ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                    @if ($item->status === 'paid')
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-green">Lunas</span>
                                    {{-- Memeriksa kondisi alternatif pada tampilan. --}}
                                    @elseif ($isOverdue)
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-red">Lewat Tempo</span>
                                    {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                                    @else
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-amber">Belum Lunas</span>
                                    {{-- Menutup percabangan kondisi pada template Blade. --}}
                                    @endif
                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                </td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ optional($item->due_date)->format('d-m-Y') ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ optional($item->paid_at)->format('d-m-Y H:i') ?? '-' }}</td>
                                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                @if (! $isKasir)
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $ageDays !== null ? $ageDays.' hari' : '-' }}</td>
                                {{-- Menutup percabangan kondisi pada template Blade. --}}
                                @endif
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                    <a href="{{ route('receivables.edit', $item->id) }}" class="btn btn-secondary btn-sm">Ubah</a>
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
