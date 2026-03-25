@extends('layouts.admin')

@section('title', 'Laporan Pengeluaran')
@section('subtitle', 'Analisis biaya operasional dan laba/rugi')

@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Laporan Pengeluaran</div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.expenses') }}" class="form-grid">
                <div class="field">
                    <label for="start_date">Dari Tanggal</label>
                    <input id="start_date" type="date" name="start_date" value="{{ request('start_date') }}">
                </div>

                <div class="field">
                    <label for="end_date">Sampai Tanggal</label>
                    <input id="end_date" type="date" name="end_date" value="{{ request('end_date') }}">
                </div>

                <div class="field">
                    <label for="expense_category_id">Kategori Biaya</label>
                    <select id="expense_category_id" name="expense_category_id">
                        <option value="">Semua Kategori</option>
                        @foreach ($expenseCategories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('expense_category_id') === (string) $category->id)>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="q">Pencarian</label>
                    <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Catatan / kategori / petugas">
                </div>

                <div class="td-actions field-full">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('reports.expenses') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card sc-red">
            <div class="sc-label">Total Biaya</div>
            <div class="sc-value mono">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            <div class="sc-sub">Akumulasi biaya operasional</div>
        </div>
        <div class="stat-card sc-green">
            <div class="sc-label">Total Penjualan</div>
            <div class="sc-value mono">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="sc-sub">{{ number_format($totalTransactions, 0, ',', '.') }} transaksi</div>
        </div>
        <div class="stat-card {{ $netProfit >= 0 ? 'sc-blue' : 'sc-amber' }}">
            <div class="sc-label">Laba / Rugi</div>
            <div class="sc-value mono">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
            <div class="sc-sub">{{ $netProfit >= 0 ? 'Laba periode terfilter' : 'Rugi periode terfilter' }}</div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Komposisi Biaya per Kategori</div>
            </div>
            <div class="card-body">
                @if ($expenseByCategory->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Belum ada data biaya untuk ditampilkan.</p>
                    </div>
                @else
                    <canvas id="expenseCategoryChart" height="120"></canvas>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-hd">
                <div class="card-title">Ringkasan Kategori</div>
            </div>
            <div class="card-body">
                @if ($expenseByCategory->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Tidak ada ringkasan kategori.</p>
                    </div>
                @else
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Nominal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($expenseByCategory as $label => $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $label }}</td>
                                    <td class="mono">Rp {{ number_format($value, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Detail Pengeluaran</div>
        </div>
        <div class="card-body">
            @if ($expenses->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada data pengeluaran pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Nominal</th>
                            <th>Catatan</th>
                            <th>Petugas</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($expense->tanggal)->format('d-m-Y') ?? '-' }}</td>
                                <td>{{ $expense->category->nama ?? '-' }}</td>
                                <td class="mono">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</td>
                                <td>{{ $expense->catatan ?: '-' }}</td>
                                <td>{{ $expense->user->name ?? '-' }}</td>
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
    (() => {
        const labels = {!! json_encode($categoryLabels) !!};
        const values = {!! json_encode($categoryTotals) !!};

        if (!labels.length || !values.length) {
            return;
        }

        const canvas = document.getElementById('expenseCategoryChart');
        if (!canvas) {
            return;
        }

        const palette = [
            'rgba(28, 77, 141, 0.82)',
            'rgba(73, 136, 196, 0.82)',
            'rgba(245, 158, 11, 0.82)',
            'rgba(16, 185, 129, 0.82)',
            'rgba(239, 68, 68, 0.82)',
            'rgba(107, 114, 128, 0.82)'
        ];

        new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: labels.map((_, index) => palette[index % palette.length]),
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: Rp ${Number(ctx.raw || 0).toLocaleString('id-ID')}`
                        }
                    }
                }
            }
        });
    })();
</script>
@endpush
