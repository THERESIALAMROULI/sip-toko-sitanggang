@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('subtitle', 'Analisis data penjualan berdasarkan filter')

@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Laporan</div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.sales') }}" class="form-grid">
                <div class="field">
                    <label for="start_date">Dari Tanggal</label>
                    <input id="start_date" type="date" name="start_date" value="{{ request('start_date') }}">
                </div>

                <div class="field">
                    <label for="end_date">Sampai Tanggal</label>
                    <input id="end_date" type="date" name="end_date" value="{{ request('end_date') }}">
                </div>

                <div class="field">
                    <label for="payment_type">Metode Pembayaran</label>
                    <select id="payment_type" name="payment_type">
                        <option value="">Semua</option>
                        <option value="cash" @selected(request('payment_type') === 'cash')>Cash</option>
                        <option value="transfer" @selected(request('payment_type') === 'transfer')>Transfer</option>
                        <option value="qris" @selected(request('payment_type') === 'qris')>QRIS</option>
                        <option value="credit" @selected(request('payment_type') === 'credit')>Kredit</option>
                    </select>
                </div>

                <div class="field">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="stat-grid" style="margin-bottom:0;grid-template-columns:1fr;">
                <div class="stat-card sc-green">
                    <div class="sc-label">Total Penjualan</div>
                    <div class="sc-value mono">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                    <div class="sc-sub">{{ $transactions->count() }} transaksi ditemukan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Grafik Penjualan</div>
        </div>
        <div class="card-body">
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Detail Transaksi</div>
        </div>
        <div class="card-body">
            @if ($transactions->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada data transaksi pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaction->transaction_date->format('d-m-Y H:i') }}</td>
                                <td>{{ $transaction->customer->name ?? '-' }}</td>
                                <td>
                                    @if ($transaction->payment_type === 'cash')
                                        <span class="badge badge-green">CASH</span>
                                    @elseif ($transaction->payment_type === 'transfer')
                                        <span class="badge badge-blue">TRANSFER</span>
                                    @elseif ($transaction->payment_type === 'qris')
                                        <span class="badge badge-purple">QRIS</span>
                                    @else
                                        <span class="badge badge-amber">KREDIT</span>
                                    @endif
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartCanvas = document.getElementById('salesChart');

    if (chartCanvas) {
        const ctx = chartCanvas.getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: {!! json_encode($chartTotals) !!},
                    backgroundColor: 'rgba(5, 150, 105, 0.72)',
                    borderColor: 'rgba(5, 150, 105, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
