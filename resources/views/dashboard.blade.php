@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas toko hari ini')

@section('content')
@php
    $role = auth()->user()->role;
@endphp

<div class="stat-grid">
    <div class="stat-card sc-green">
        <div class="sc-label">Penjualan Hari Ini</div>
        <div class="sc-value mono">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
        <div class="sc-sub">{{ $todayTransactionCount }} transaksi hari ini</div>
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
        <div class="sc-sub">{{ $overdueReceivablesCount }} piutang lewat jatuh tempo</div>
    </div>
</div>

<div class="grid-2 mb-4">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Akses Cepat</div>
            <span class="badge badge-blue">{{ now()->translatedFormat('d M Y') }}</span>
        </div>
        <div class="card-body">
            <div class="quick-actions">
                @if ($role === 'admin')
                    <a href="{{ route('users.index') }}" class="quick-card">
                        <div class="qc-icon">U</div>
                        <div class="qc-label">Manajemen User</div>
                        <div class="qc-sub">Kelola akun sistem</div>
                    </a>
                    <a href="{{ route('kategoris.index') }}" class="quick-card">
                        <div class="qc-icon">K</div>
                        <div class="qc-label">Kategori</div>
                        <div class="qc-sub">Master kategori produk</div>
                    </a>
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
                    <a href="{{ route('expense_categories.index') }}" class="quick-card">
                        <div class="qc-icon">B</div>
                        <div class="qc-label">Kategori Biaya</div>
                        <div class="qc-sub">Klasifikasi pengeluaran</div>
                    </a>
                    <a href="{{ route('expenses.index') }}" class="quick-card">
                        <div class="qc-icon">O</div>
                        <div class="qc-label">Biaya Operasional</div>
                        <div class="qc-sub">Catat pengeluaran toko</div>
                    </a>
                @endif

                @if ($role === 'kasir')
                    <a href="{{ route('transactions.create') }}" class="quick-card">
                        <div class="qc-icon">T</div>
                        <div class="qc-label">Transaksi Baru</div>
                        <div class="qc-sub">Input penjualan</div>
                    </a>
                    <a href="{{ route('receivables.index') }}" class="quick-card">
                        <div class="qc-icon">R</div>
                        <div class="qc-label">Piutang</div>
                        <div class="qc-sub">Pantau kredit</div>
                    </a>
                    <a href="{{ route('stocks.check') }}" class="quick-card">
                        <div class="qc-icon">C</div>
                        <div class="qc-label">Cek Stok</div>
                        <div class="qc-sub">Pantau ketersediaan</div>
                    </a>
                @endif

                @if (in_array($role, ['admin', 'owner'], true))
                    <a href="{{ route('reports.sales') }}" class="quick-card">
                        <div class="qc-icon">L</div>
                        <div class="qc-label">Laporan Penjualan</div>
                        <div class="qc-sub">Analisis penjualan</div>
                    </a>
                    <a href="{{ route('reports.receivables') }}" class="quick-card">
                        <div class="qc-icon">U</div>
                        <div class="qc-label">Laporan Utang</div>
                        <div class="qc-sub">Rekap piutang</div>
                    </a>
                    <a href="{{ route('reports.stock') }}" class="quick-card">
                        <div class="qc-icon">S</div>
                        <div class="qc-label">Laporan Stok</div>
                        <div class="qc-sub">Mutasi & persediaan</div>
                    </a>
                    <a href="{{ route('reports.expenses') }}" class="quick-card">
                        <div class="qc-icon">B</div>
                        <div class="qc-label">Laporan Pengeluaran</div>
                        <div class="qc-sub">Total biaya & laba/rugi</div>
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
            <div class="card-title">Perbandingan Bulan Ini</div>
        </div>
        <div class="card-body stack-md">
            <div>
                <div class="sc-label">Penjualan Bulan Ini</div>
                <div class="sc-value mono">Rp {{ number_format($thisMonthSales, 0, ',', '.') }}</div>
            </div>
            <div>
                <div class="sc-label">Penjualan Bulan Lalu</div>
                <div class="sc-value mono">Rp {{ number_format($lastMonthSales, 0, ',', '.') }}</div>
            </div>
            <div class="alert {{ $salesGrowthPercent >= 0 ? 'alert-success' : 'alert-danger' }} mb-0">
                Tren:
                <strong>
                    {{ $salesGrowthPercent >= 0 ? '+' : '' }}{{ number_format($salesGrowthPercent, 1, ',', '.') }}%
                </strong>
                dibanding bulan lalu.
            </div>
        </div>
    </div>
</div>

<div class="grid-2 mb-4">
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
                                    <span class="badge {{ $transaction->payment_type === 'utang' ? 'badge-amber' : 'badge-blue' }}">
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

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Produk Stok Menipis</div>
        </div>
        <div class="card-body">
            @if ($lowStockProducts->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada produk dengan stok kritis.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($lowStockProducts as $product)
                            <tr>
                                <td>{{ $product->nama }}</td>
                                <td class="mono">{{ $product->stok }}</td>
                                <td>
                                    @if ($product->stok <= 0)
                                        <span class="badge badge-red">Habis</span>
                                    @else
                                        <span class="badge badge-amber">Rendah</span>
                                    @endif
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

@if ($role === 'owner')
    <div class="alert {{ $outOfStockCount > 0 ? 'alert-danger' : 'alert-success' }} mb-4">
        Produk habis saat ini: <strong>{{ $outOfStockCount }}</strong>.
        @if ($outOfStockCount > 0)
            Segera lakukan restok agar penjualan tidak terganggu.
        @else
            Semua produk masih tersedia.
        @endif
    </div>

    <div class="grid-2 mb-4">
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Top 5 Produk Terlaris (12 Bulan)</div>
            </div>
            <div class="card-body">
                @if ($ownerTopProducts->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Belum ada data produk terjual.</p>
                    </div>
                @else
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($ownerTopProducts as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="mono">{{ number_format($item->qty_sold, 0, ',', '.') }}</td>
                                    <td class="mono">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-hd">
                <div class="card-title">Top 5 Utang Terlama</div>
            </div>
            <div class="card-body">
                @if ($ownerOldestReceivables->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Tidak ada utang aktif.</p>
                    </div>
                @else
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Transaksi</th>
                                <th>Lama</th>
                                <th>Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($ownerOldestReceivables as $item)
                                <tr>
                                    <td>{{ $item->transaction->customer->name ?? '-' }}</td>
                                    <td>#{{ $item->transaction_id }}</td>
                                    <td>{{ $item->created_at ? $item->created_at->diffInDays(now()) : 0 }} hari</td>
                                    <td class="mono">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid-2 mb-4">
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Tren Penjualan 12 Bulan</div>
            </div>
            <div class="card-body">
                <canvas id="ownerSalesTrendChart" height="110"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-hd">
                <div class="card-title">Grafik Penjualan vs Piutang</div>
            </div>
            <div class="card-body">
                <canvas id="ownerSalesVsReceivablesChart" height="110"></canvas>
            </div>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-hd">
        <div class="card-title">Piutang Lewat Jatuh Tempo</div>
        <span class="badge badge-red">
            Rp {{ number_format($overdueReceivablesAmount, 0, ',', '.') }}
        </span>
    </div>
    <div class="card-body">
        @if ($overdueReceivables->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Tidak ada piutang yang melewati jatuh tempo.</p>
            </div>
        @else
            <div class="tbl-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Transaksi</th>
                        <th>Tanggal Utang</th>
                        <th>Jumlah</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($overdueReceivables as $receivable)
                        <tr>
                            <td>{{ $receivable->transaction->customer->name ?? '-' }}</td>
                            <td>#{{ $receivable->transaction_id }}</td>
                            <td>{{ optional($receivable->created_at)->format('d-m-Y') ?? '-' }}</td>
                            <td class="mono">Rp {{ number_format($receivable->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@if ($role === 'owner')
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const labels = {!! json_encode($ownerTrendLabels) !!};
            const salesValues = {!! json_encode($ownerSalesTrendValues) !!};
            const receivableValues = {!! json_encode($ownerReceivableTrendValues) !!};

            const salesTrendCanvas = document.getElementById('ownerSalesTrendChart');
            if (salesTrendCanvas) {
                new Chart(salesTrendCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Penjualan',
                            data: salesValues,
                            borderColor: 'rgba(28, 77, 141, 1)',
                            backgroundColor: 'rgba(73, 136, 196, 0.2)',
                            tension: 0.35,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => 'Rp ' + Number(value).toLocaleString('id-ID')
                                }
                            }
                        }
                    }
                });
            }

            const compareCanvas = document.getElementById('ownerSalesVsReceivablesChart');
            if (compareCanvas) {
                new Chart(compareCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Penjualan',
                            data: salesValues,
                            backgroundColor: 'rgba(28, 77, 141, 0.75)',
                            borderRadius: 6
                        }, {
                            label: 'Piutang',
                            data: receivableValues,
                            backgroundColor: 'rgba(73, 136, 196, 0.75)',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => 'Rp ' + Number(value).toLocaleString('id-ID')
                                }
                            }
                        }
                    }
                });
            }
        })();
    </script>
    @endpush
@endif
