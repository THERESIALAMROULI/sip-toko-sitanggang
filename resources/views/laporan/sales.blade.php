@extends('layouts.admin')
@section('title', 'Laporan Penjualan')
@section('subtitle', 'Analisis data penjualan berdasarkan filter')
@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Laporan</div>
            <div class="td-actions">
                <button type="button" class="btn btn-secondary" id="exportSalesExcel">Unduh Excel</button>
                <button type="button" class="btn btn-outline" id="exportSalesPdf">Unduh PDF</button>
            </div>
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
                        <option value="tunai" @selected(request('payment_type') === 'tunai')>Tunai</option>
                        <option value="utang" @selected(request('payment_type') === 'utang')>Utang</option>
                    </select>
                </div>
                <div class="field">
                    <label for="customer_id">Pelanggan</label>
                    <select id="customer_id" name="customer_id">
                        <option value="">Semua Pelanggan</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected((string) request('customer_id') === (string) $customer->id)>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="td-actions field-full">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('reports.sales') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="stat-grid">
        <div class="stat-card sc-green">
            <div class="sc-label">Total Penjualan</div>
            <div class="sc-value mono">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="sc-sub">Akumulasi nominal transaksi</div>
        </div>
        <div class="stat-card sc-blue">
            <div class="sc-label">Jumlah Transaksi</div>
            <div class="sc-value">{{ number_format($totalTransactions, 0, ',', '.') }}</div>
            <div class="sc-sub">Data transaksi pada periode terfilter</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="sc-label">Rata-rata per Transaksi</div>
            <div class="sc-value mono">Rp {{ number_format($averagePerTransaction, 0, ',', '.') }}</div>
            <div class="sc-sub">Rata-rata nilai tiap transaksi</div>
        </div>
        <div class="stat-card sc-purple">
            <div class="sc-label">Total Utang</div>
            <div class="sc-value mono">Rp {{ number_format($creditSales, 0, ',', '.') }}</div>
            <div class="sc-sub">Nilai transaksi dengan metode utang</div>
        </div>
    </div>
    <div class="grid-2">
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
                <div class="card-title">Produk Terlaris</div>
            </div>
            <div class="card-body">
                @if ($topProducts->isEmpty())
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
                                <th>Qty Terjual</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($topProducts as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ number_format($item->qty_sold, 0, ',', '.') }}</td>
                                    <td class="mono">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
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
                                    @if ($transaction->payment_type === 'tunai')
                                        <span class="badge badge-green">TUNAI</span>
                                    @else
                                        <span class="badge badge-amber">UTANG</span>
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
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    const salesExportData = {
        transactions: {!! json_encode($transactions->map(fn ($item) => [
            'date' => optional($item->transaction_date)->format('d-m-Y H:i'),
            'customer' => $item->customer->name ?? '-',
            'payment' => strtoupper($item->payment_type),
            'total' => (int) $item->total,
        ])->values()) !!},
        topProducts: {!! json_encode($topProducts->map(fn ($item) => [
            'product' => $item->product_name,
            'qty' => (int) $item->qty_sold,
            'total' => (int) $item->total_sales,
        ])->values()) !!},
        summary: {
            totalSales: {{ (int) $totalSales }},
            totalTransactions: {{ (int) $totalTransactions }},
            averagePerTransaction: {{ (int) $averagePerTransaction }},
            creditSales: {{ (int) $creditSales }}
        }
    };
    const formatRupiah = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;
    const createExcelSheet = (title, headers, rows, summaryRows = []) => {
        const aoa = [[title], []];
        summaryRows.forEach((row) => aoa.push(row));
        if (summaryRows.length) {
            aoa.push([]);
        }
        aoa.push(headers);
        rows.forEach((row) => aoa.push(row));
        const worksheet = XLSX.utils.aoa_to_sheet(aoa);
        const headerRowIndex = aoa.length - rows.length - 1;
        worksheet['!autofilter'] = {
            ref: XLSX.utils.encode_range({
                s: { r: headerRowIndex, c: 0 },
                e: { r: headerRowIndex, c: headers.length - 1 }
            })
        };
        worksheet['!cols'] = headers.map((header, colIndex) => {
            const lengths = rows.map((row) => String(row[colIndex] ?? '').length);
            const maxLength = Math.max(String(header).length, ...lengths, 12);
            return { wch: Math.min(maxLength + 2, 45) };
        });
        return worksheet;
    };
    const excelBtn = document.getElementById('exportSalesExcel');
    if (excelBtn) {
        excelBtn.addEventListener('click', () => {
            if (typeof XLSX === 'undefined') {
                window.alert('Modul Excel belum termuat. Silakan coba lagi.');
                return;
            }
            const transactionRows = salesExportData.transactions.map((item, index) => [
                index + 1,
                item.date,
                item.customer,
                item.payment,
                item.total
            ]);
            const topProductRows = salesExportData.topProducts.map((item, index) => [
                index + 1,
                item.product,
                item.qty,
                item.total
            ]);
            const workbook = XLSX.utils.book_new();
            const transactionSheet = createExcelSheet(
                'Laporan Penjualan',
                ['No', 'Tanggal', 'Pelanggan', 'Pembayaran', 'Total'],
                transactionRows,
                [
                    ['Total Penjualan', salesExportData.summary.totalSales],
                    ['Jumlah Transaksi', salesExportData.summary.totalTransactions],
                    ['Rata-rata Transaksi', salesExportData.summary.averagePerTransaction],
                    ['Total Utang', salesExportData.summary.creditSales]
                ]
            );
            const topProductSheet = createExcelSheet(
                'Top Produk Terlaris',
                ['No', 'Produk', 'Qty Terjual', 'Total'],
                topProductRows
            );
            XLSX.utils.book_append_sheet(workbook, transactionSheet, 'Penjualan');
            XLSX.utils.book_append_sheet(workbook, topProductSheet, 'Top Produk');
            XLSX.writeFile(workbook, 'laporan-penjualan.xlsx');
        });
    }
    const pdfBtn = document.getElementById('exportSalesPdf');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape' });
            doc.setFontSize(14);
            doc.text('Laporan Penjualan', 14, 14);
            doc.setFontSize(10);
            doc.text(`Total Penjualan: ${formatRupiah(salesExportData.summary.totalSales)}`, 14, 22);
            doc.text(`Jumlah Transaksi: ${salesExportData.summary.totalTransactions}`, 14, 28);
            doc.text(`Rata-rata: ${formatRupiah(salesExportData.summary.averagePerTransaction)}`, 14, 34);
            doc.text(`Total Utang: ${formatRupiah(salesExportData.summary.creditSales)}`, 14, 40);
            doc.autoTable({
                startY: 48,
                head: [['No', 'Tanggal', 'Pelanggan', 'Pembayaran', 'Total']],
                body: salesExportData.transactions.map((item, index) => [
                    index + 1,
                    item.date,
                    item.customer,
                    item.payment,
                    formatRupiah(item.total)
                ]),
                styles: { fontSize: 8 }
            });
            const nextY = doc.lastAutoTable.finalY + 10;
            doc.text('Top Produk Terlaris', 14, nextY);
            doc.autoTable({
                startY: nextY + 4,
                head: [['Produk', 'Qty', 'Total']],
                body: salesExportData.topProducts.map((item) => [
                    item.product,
                    item.qty,
                    formatRupiah(item.total)
                ]),
                styles: { fontSize: 8 }
            });
            doc.save('laporan-penjualan.pdf');
        });
    }
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
                    backgroundColor: 'rgba(28, 77, 141, 0.72)',
                    borderColor: 'rgba(28, 77, 141, 1)',
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
