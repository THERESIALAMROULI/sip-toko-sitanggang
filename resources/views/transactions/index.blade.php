@extends('layouts.admin')

@section('title', 'Transaksi')
@section('subtitle', 'Riwayat transaksi penjualan')

@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Data Transaksi</div>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">+ Transaksi Baru</a>
    </div>
    <div class="card-body">
        @if ($transactions->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Belum ada transaksi.</p>
            </div>
        @else
            <div class="tbl-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Pembayaran</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($transactions as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($t->transaction_date)->format('d-m-Y H:i') }}</td>
                            <td>{{ $t->customer->name ?? '-' }}</td>
                            <td>
                                @if ($t->payment_type === 'cash')
                                    <span class="badge badge-green">CASH</span>
                                @elseif ($t->payment_type === 'transfer')
                                    <span class="badge badge-blue">TRANSFER</span>
                                @elseif ($t->payment_type === 'qris')
                                    <span class="badge badge-purple">QRIS</span>
                                @else
                                    <span class="badge badge-amber">KREDIT</span>
                                @endif
                            </td>
                            <td class="mono">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
