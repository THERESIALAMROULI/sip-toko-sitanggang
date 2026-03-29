@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('subtitle', 'Rincian transaksi dan item penjualan')

@section('content')
@php
    $isKasir = (auth()->user()->role ?? null) === 'kasir';
@endphp
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Informasi Transaksi #{{ $transaction->id }}</div>
            <div class="td-actions">
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="button" onclick="window.print()" class="btn btn-outline">Cetak</button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="field">
                    <label>Tanggal</label>
                    <input type="text" value="{{ optional($transaction->transaction_date)->format('d-m-Y H:i') }}" readonly>
                </div>
                <div class="field">
                    <label>Pelanggan</label>
                    <input type="text" value="{{ $transaction->customer->name ?? 'Umum / Tanpa Pelanggan' }}" readonly>
                </div>
                <div class="field">
                    <label>Metode Pembayaran</label>
                    <input type="text" value="{{ strtoupper($transaction->payment_type) }}" readonly>
                </div>
                <div class="field">
                    <label>Kasir</label>
                    <input type="text" value="{{ $isKasir ? 'Bony' : ($transaction->user->name ?? '-') }}" readonly>
                </div>
                <div class="field">
                    <label>Total</label>
                    <input type="text" class="mono" value="Rp {{ number_format($transaction->total, 0, ',', '.') }}" readonly>
                </div>
            </div>
        </div>
    </div>

    @if ($transaction->payment_type === 'tunai')
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Informasi Pembayaran Tunai</div>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="field">
                        <label>Uang Diterima</label>
                        <input type="text" class="mono" value="Rp {{ number_format($transaction->cash_received ?? 0, 0, ',', '.') }}" readonly>
                    </div>
                    <div class="field">
                        <label>Kembalian</label>
                        <input type="text" class="mono" value="Rp {{ number_format($transaction->change_amount ?? 0, 0, ',', '.') }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Detail Item</div>
        </div>
        <div class="card-body">
            <div class="tbl-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($transaction->details as $detail)
                        <tr>
                            <td>{{ $detail->product->name ?? '-' }}</td>
                            <td class="mono">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td class="mono">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada item pada transaksi ini.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($transaction->payment_type === 'utang' && $transaction->receivable)
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Informasi Piutang</div>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="field">
                        <label>Status</label>
                        <input type="text" value="{{ $transaction->receivable->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}" readonly>
                    </div>
                    <div class="field">
                        <label>Tanggal Pelunasan</label>
                        <input type="text" value="{{ optional($transaction->receivable->paid_at)->format('d-m-Y H:i') ?? '-' }}" readonly>
                    </div>
                    <div class="field">
                        <label>Jatuh Tempo</label>
                        <input type="text" value="{{ optional($transaction->receivable->due_date)->format('d-m-Y') ?? '-' }}" readonly>
                    </div>
                    <div class="field field-full">
                        <label>Nominal</label>
                        <input type="text" class="mono" value="Rp {{ number_format($transaction->receivable->amount, 0, ',', '.') }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
