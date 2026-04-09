{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Ubah Status Piutang')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Perbarui status pembayaran piutang')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="card">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-hd">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-title">Form Ubah Status</div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card-body">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="alert alert-info">
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            Transaksi: <strong>#{{ $receivable->transaction_id }}</strong> |
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            Customer: <strong>{{ $receivable->transaction->customer->name ?? '-' }}</strong> |
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            Nominal: <strong>Rp {{ number_format($receivable->amount, 0, ',', '.') }}</strong> |
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            Jatuh Tempo: <strong>{{ optional($receivable->due_date)->format('d-m-Y') ?? '-' }}</strong>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
        <form action="{{ route('receivables.update', $receivable->id) }}" method="POST" class="stack-md">
            {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
            @csrf
            {{-- Menentukan spoofing method HTTP agar form bisa memakai PUT, PATCH, atau DELETE. --}}
            @method('PUT')

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="status">Status</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="status" name="status" required>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="unpaid" @selected(old('status', $receivable->status) === 'unpaid')>Belum Lunas</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="paid" @selected(old('status', $receivable->status) === 'paid')>Lunas</option>
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label>Tanggal Pelunasan Saat Ini</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input type="text" value="{{ optional($receivable->paid_at)->format('d-m-Y H:i') ?? '-' }}" readonly>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="td-actions">
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="submit" class="btn btn-primary">Simpan</button>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('receivables.index') }}" class="btn btn-secondary">Batal</a>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
        </form>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection
