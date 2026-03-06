@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas toko hari ini')

@section('content')
@php
    $role = auth()->user()->role;
    $todaySales = \App\Models\Transaction::query()
        ->whereDate('transaction_date', today())
        ->sum('total');
    $totalProducts = \App\Models\Product::query()->count();
    $totalCustomers = \App\Models\Customer::query()->count();
    $outstandingReceivables = \App\Models\Receivable::query()
        ->where('status', 'unpaid')
        ->sum('amount');
    $latestTransactions = \App\Models\Transaction::query()
        ->with('customer')
        ->latest('transaction_date')
        ->limit(6)
        ->get();
@endphp

<div class="stat-grid">
    <div class="stat-card sc-green">
        <div class="sc-label">Penjualan Hari Ini</div>
        <div class="sc-value mono">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
        <div class="sc-sub">{{ now()->translatedFormat('l, d M Y') }}</div>
    </div>

    <div class="stat-card sc-blue">
        <div class="sc-label">Total Produk</div>
        <div class="sc-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
        <div class="sc-sub">Produk aktif tersimpan</div>
    </div>

    <div class="stat-card sc-amber">
        <div class="sc-label">Total Pelanggan</div>
        <div class="sc-value">{{ number_format($totalCustomers, 0, ',', '.') }}</div>
        <div class="sc-sub">Data customer terdaftar</div>
    </div>

    <div class="stat-card sc-purple">
        <div class="sc-label">Piutang Berjalan</div>
        <div class="sc-value mono">Rp {{ number_format($outstandingReceivables, 0, ',', '.') }}</div>
        <div class="sc-sub">Status belum lunas</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Akses Cepat</div>
        </div>
        <div class="card-body">
            <div class="quick-actions">
                @if ($role === 'admin')
                    <a href="{{ route('products.index') }}" class="quick-card">
                        <div class="qc-icon">P</div>
                        <div class="qc-label">Data Produk</div>
                        <div class="qc-sub">Kelola item jual</div>
                    </a>
                    <a href="{{ route('customers.index') }}" class="quick-card">
                        <div class="qc-icon">C</div>
                        <div class="qc-label">Pelanggan</div>
                        <div class="qc-sub">Master customer</div>
                    </a>
                @endif

                @if (in_array($role, ['admin', 'kasir'], true))
                    <a href="{{ route('transactions.index') }}" class="quick-card">
                        <div class="qc-icon">T</div>
                        <div class="qc-label">Transaksi</div>
                        <div class="qc-sub">Catat penjualan</div>
                    </a>
                    <a href="{{ route('receivables.index') }}" class="quick-card">
                        <div class="qc-icon">R</div>
                        <div class="qc-label">Piutang</div>
                        <div class="qc-sub">Pantau kredit</div>
                    </a>
                @endif

                @if (in_array($role, ['admin', 'owner'], true))
                    <a href="{{ route('reports.sales') }}" class="quick-card">
                        <div class="qc-icon">L</div>
                        <div class="qc-label">Laporan</div>
                        <div class="qc-sub">Analisis penjualan</div>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="quick-card">
                    <div class="qc-icon">A</div>
                    <div class="qc-label">Profil</div>
                    <div class="qc-sub">Ubah akun</div>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Transaksi Terbaru</div>
        </div>
        <div class="card-body">
            @if ($latestTransactions->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Belum ada transaksi terbaru.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($latestTransactions as $transaction)
                            <tr>
                                <td>{{ optional($transaction->transaction_date)->format('d-m-Y H:i') }}</td>
                                <td>{{ $transaction->customer->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $transaction->payment_type === 'credit' ? 'badge-amber' : 'badge-blue' }}">
                                        {{ strtoupper($transaction->payment_type) }}
                                    </span>
                                </td>
                                <td class="mono">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
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
