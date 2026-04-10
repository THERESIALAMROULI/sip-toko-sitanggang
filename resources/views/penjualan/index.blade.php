@extends('layouts.admin')
@section('title', 'Transaksi')
@section('subtitle', 'Riwayat transaksi penjualan')
@section('content')
@php
    $isKasir = (auth()->user()->role ?? null) === 'kasir';
@endphp
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Transaksi</div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('transactions.index') }}" class="form-grid">
                <div class="field">
                    <label for="q">Cari</label>
                    <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="ID transaksi / nama pelanggan">
                </div>
                <div class="field">
                    <label for="payment_type">Metode Pembayaran</label>
                    <select id="payment_type" name="payment_type">
                        <option value="">Semua</option>
                        <option value="tunai" @selected(($filters['payment_type'] ?? null) === 'tunai')>Tunai</option>
                        <option value="utang" @selected(($filters['payment_type'] ?? null) === 'utang')>Utang</option>
                    </select>
                </div>
                <div class="field">
                    <label for="start_date">Dari Tanggal</label>
                    <input id="start_date" type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
                </div>
                <div class="field">
                    <label for="end_date">Sampai Tanggal</label>
                    <input id="end_date" type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}">
                </div>
                <div class="td-actions field-full">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Reset</a>
                    <a href="{{ route('transactions.create') }}" class="btn btn-outline">+ Transaksi Baru</a>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Data Transaksi</div>
            <span class="badge badge-blue">{{ $transactions->count() }} transaksi</span>
        </div>
        <div class="card-body">
            @if ($transactions->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Belum ada transaksi pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                            <th>Status Piutang</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td class="mono">#{{ $transaction->id }}</td>
                                <td>{{ optional($transaction->transaction_date)->format('d-m-Y H:i') }}</td>
                                <td>{{ $transaction->customer->name ?? '-' }}</td>
                                <td>{{ $isKasir ? 'Bony' : ($transaction->user->name ?? '-') }}</td>
                                <td>
                                    @if ($transaction->payment_type === 'tunai')
                                        <span class="badge badge-green">TUNAI</span>
                                    @else
                                        <span class="badge badge-amber">UTANG</span>
                                    @endif
                                </td>
                                <td class="mono">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                <td>
                                    @if ($transaction->payment_type !== 'utang')
                                        <span class="badge badge-gray">Tidak ada</span>
                                    @elseif (($transaction->receivable->status ?? 'unpaid') === 'paid')
                                        <span class="badge badge-green">Lunas</span>
                                    @else
                                        <span class="badge badge-amber">Belum lunas</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-secondary btn-sm">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
