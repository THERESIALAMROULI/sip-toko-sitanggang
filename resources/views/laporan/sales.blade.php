@extends('layouts.admin')
@section('title', 'Laporan Penjualan')
@section('subtitle', 'Laporan penjualan')

@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div>
                <div class="card-title">Filter Laporan</div>
            </div>
            <div class="td-actions">
                <button type="button" class="btn btn-secondary" id="exportSalesExcel">Unduh Excel</button>
                <button type="button" class="btn btn-outline" id="exportSalesPdf">Unduh PDF</button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.sales') }}" class="form-grid">
                <input type="hidden" name="sale_status" value="{{ $selectedSaleStatus }}">
                <div class="field">
                    <label for="period">Periode</label>
                    <select id="period" name="period">
                        <option value="all" @selected($selectedPeriod === 'all')>Semua Periode</option>
                        <option value="daily" @selected($selectedPeriod === 'daily')>Harian</option>
                        <option value="weekly" @selected($selectedPeriod === 'weekly')>Mingguan</option>
                        <option value="monthly" @selected($selectedPeriod === 'monthly')>Bulanan</option>
                        <option value="custom" @selected($selectedPeriod === 'custom')>Custom</option>
                    </select>
                </div>
                <div class="field">
                    <label for="start_date">Dari Tanggal</label>
                    <input id="start_date" type="date" name="start_date" value="{{ $displayStartDate }}">
                </div>
                <div class="field">
                    <label for="end_date">Sampai Tanggal</label>
                    <input id="end_date" type="date" name="end_date" value="{{ $displayEndDate }}">
                </div>
                <div class="field">
                    <label for="payment_type">Pembayaran</label>
                    <select id="payment_type" name="payment_type">
                        <option value="">Semua</option>
                        <option value="tunai" @selected(request('payment_type') === 'tunai')>Tunai</option>
                        <option value="utang" @selected(request('payment_type') === 'utang')>Utang</option>
                    </select>
                </div>
                <div class="field field-full">
                    <label for="q">Pencarian</label>
                    <input id="q" type="text" name="q" placeholder="Cari pembeli, produk, kategori barang, atau nota..." value="{{ $search }}">
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
            <div class="sc-label">Total Omzet</div>
            <div class="sc-value mono">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="sc-sub">Menyesuaikan filter laporan yang sedang dipilih</div>
        </div>
        <div class="stat-card sc-blue">
            <div class="sc-label">Untung Penjualan</div>
            <div class="sc-value mono">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
            <div class="sc-sub">Omzet dikurangi modal beli barang yang terjual</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="sc-label">Total Penjualan Utang</div>
            <div class="sc-value mono">Rp {{ number_format($creditSales, 0, ',', '.') }}</div>
            <div class="sc-sub">Total transaksi utang pada filter aktif</div>
        </div>
        <div class="stat-card sc-purple">
            <div class="sc-label">Rata-rata Transaksi</div>
            <div class="sc-value mono">Rp {{ number_format($averagePerTransaction, 0, ',', '.') }}</div>
            <div class="sc-sub">Nilai rata-rata per transaksi</div>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div>
                <div class="card-title">Grafik Penjualan</div>
                <div class="table-sub">{{ $chartGranularityLabel }} | {{ $chartCurrentPeriodLabel }}</div>
            </div>
        </div>
        <div class="card-body">
            @if ($chartLabels->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Belum ada data penjualan untuk divisualisasikan.</p>
                </div>
            @else
                <div class="sales-report-chart-wrap">
                    <canvas id="salesChart" height="260"></canvas>
                </div>
            @endif
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
                            <th>No</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Jumlah Terjual</th>
                            <th>Omzet</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($topProducts as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->category_name ?? '-' }}</td>
                                <td>{{ number_format((int) $item->qty_sold, 0, ',', '.') }}</td>
                                <td class="mono">Rp {{ number_format((int) $item->total_sales, 0, ',', '.') }}</td>
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
            <div>
                <div class="card-title">Tabel Detail Penjualan</div>
            </div>
            <span class="badge badge-gray">{{ number_format($salesDetails->total(), 0, ',', '.') }} baris</span>
        </div>
        <div class="card-body stack-md">
            <div class="product-group-tabs">
                @foreach ($detailGroups as $group)
                    <a
                        href="{{ route('reports.sales', array_merge(request()->except(['page', 'sale_status']), ['sale_status' => $group['key']])) }}"
                        class="btn {{ $selectedSaleStatus === $group['key'] ? 'btn-primary' : 'btn-secondary' }}"
                    >
                        {{ $group['label'] }} ({{ number_format($group['count'], 0, ',', '.') }})
                    </a>
                @endforeach
            </div>

            @if ($salesDetails->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada detail penjualan pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>ID Transaksi</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($salesDetails as $detail)
                            <tr>
                                <td>{{ $salesDetails->firstItem() + $loop->index }}</td>
                                <td>{{ optional($detail->transaction->transaction_date)->format('d-m-Y H:i') ?? '-' }}</td>
                                <td>{{ $detail->transaction->getAttribute('no_nota') ?: 'TRX-'.$detail->transaction_id }}</td>
                                <td>{{ $detail->transaction->customer->name ?? 'Umum' }}</td>
                                <td>{{ $detail->nama_produk }}</td>
                                <td>{{ $detail->product->kategori->nama ?? '-' }}</td>
                                <td>{{ number_format((int) $detail->quantity, 0, ',', '.') }}</td>
                                <td class="mono">Rp {{ number_format((int) $detail->subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $detail->status_badge }}">{{ $detail->status_label }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($salesDetails->hasPages())
                    <div class="pagination-bar">
                        <div class="table-sub">
                            Menampilkan {{ $salesDetails->firstItem() }}-{{ $salesDetails->lastItem() }} dari {{ $salesDetails->total() }} baris
                        </div>
                        <div class="pagination-pages">
                            @if ($salesDetails->onFirstPage())
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $salesDetails->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                            @endif

                            @foreach ($salesDetails->getUrlRange(1, $salesDetails->lastPage()) as $page => $url)
                                @if ($page === $salesDetails->currentPage())
                                    <span class="btn btn-primary btn-sm" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="btn btn-secondary btn-sm">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($salesDetails->hasMorePages())
                                <a href="{{ $salesDetails->nextPageUrl() }}" class="btn btn-secondary btn-sm">Berikutnya</a>
                            @else
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Berikutnya</span>
                            @endif
                        </div>
                    </div>
                @endif
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
        summary: {
            totalSales: {{ (int) $totalSales }},
            totalTransactions: {{ (int) $totalTransactions }},
            totalItemsSold: {{ (int) $totalItemsSold }},
            averagePerTransaction: {{ (int) $averagePerTransaction }},
            totalProfit: {{ (int) $totalProfit }},
            creditSales: {{ (int) $creditSales }},
            comparisonTotalSales: {{ (int) ($comparisonTotalSales ?? 0) }},
            salesDifferenceAmount: {{ (int) ($salesDifferenceAmount ?? 0) }},
            currentPeriodLabel: @json($currentPeriodLabel),
            comparisonPeriodLabel: @json($comparisonPeriodLabel)
        },
        details: {!! json_encode($exportSalesDetails->map(function ($detail) {
            $status = match ($detail->transaction->status ?? null) {
                'lunas' => 'Lunas',
                'utang' => 'Utang',
                default => 'Diproses',
            };

            return [
                'date' => optional($detail->transaction->transaction_date)->format('d-m-Y H:i') ?? '-',
                'invoice' => $detail->transaction->getAttribute('no_nota') ?: 'TRX-'.$detail->transaction_id,
                'customer' => $detail->transaction->customer->name ?? 'Umum',
                'product' => $detail->nama_produk,
                'category' => $detail->product->kategori->nama ?? '-',
                'quantity' => (int) $detail->quantity,
                'total' => (int) $detail->subtotal,
                'status' => $status,
            ];
        })->values()) !!},
        topProducts: {!! json_encode($topProducts->map(function ($item) {
            return [
                'product' => $item->product_name,
                'category' => $item->category_name ?? '-',
                'qty' => (int) $item->qty_sold,
                'total' => (int) $item->total_sales,
            ];
        })->values()) !!},
        chart: {
            labels: {!! json_encode($chartLabels) !!},
            currentTotals: {!! json_encode($chartTotals) !!},
            comparisonTotals: {!! json_encode($comparisonChartTotals) !!},
            currentPeriodLabel: @json($chartCurrentPeriodLabel),
            comparisonPeriodLabel: @json($chartComparisonPeriodLabel)
        }
    };

    const formatRupiah = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;
    const formatCompactRupiah = (value) => {
        const numericValue = Number(value || 0);
        return new Intl.NumberFormat('id-ID', {
            notation: 'compact',
            compactDisplay: 'short',
            maximumFractionDigits: 1
        }).format(numericValue);
    };

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

            const workbook = XLSX.utils.book_new();
            const summarySheet = createExcelSheet(
                'Ringkasan Laporan Penjualan',
                ['Keterangan', 'Nilai'],
                [
                    ['Periode Aktif', salesExportData.summary.currentPeriodLabel],
                    ['Periode Pembanding', salesExportData.summary.comparisonPeriodLabel || '-'],
                    ['Total Omzet', salesExportData.summary.totalSales],
                    ['Untung Penjualan', salesExportData.summary.totalProfit],
                    ['Total Barang Terjual', salesExportData.summary.totalItemsSold],
                    ['Rata-rata Transaksi', salesExportData.summary.averagePerTransaction],
                    ['Penjualan Utang', salesExportData.summary.creditSales],
                    ['Selisih Penjualan', salesExportData.summary.comparisonPeriodLabel ? salesExportData.summary.salesDifferenceAmount : '-']
                ]
            );

            const detailSheet = createExcelSheet(
                'Detail Penjualan',
                ['No', 'Tanggal', 'ID Transaksi', 'Pembeli', 'Produk', 'Kategori', 'Jumlah', 'Total Harga', 'Status'],
                salesExportData.details.map((item, index) => [
                    index + 1,
                    item.date,
                    item.invoice,
                    item.customer,
                    item.product,
                    item.category,
                    item.quantity,
                    item.total,
                    item.status
                ])
            );

            const topProductSheet = createExcelSheet(
                'Produk Terlaris',
                ['No', 'Produk', 'Kategori', 'Jumlah Terjual', 'Omzet'],
                salesExportData.topProducts.map((item, index) => [
                    index + 1,
                    item.product,
                    item.category,
                    item.qty,
                    item.total
                ])
            );

            XLSX.utils.book_append_sheet(workbook, summarySheet, 'Ringkasan');
            XLSX.utils.book_append_sheet(workbook, detailSheet, 'Detail Penjualan');
            XLSX.utils.book_append_sheet(workbook, topProductSheet, 'Produk Terlaris');
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
            doc.text(`Periode Aktif: ${salesExportData.summary.currentPeriodLabel}`, 14, 22);
            doc.text(`Periode Pembanding: ${salesExportData.summary.comparisonPeriodLabel || '-'}`, 14, 28);
            doc.text(`Total Omzet: ${formatRupiah(salesExportData.summary.totalSales)}`, 14, 34);
            doc.text(`Untung Penjualan: ${formatRupiah(salesExportData.summary.totalProfit)}`, 14, 40);
            doc.text(`Total Barang Terjual: ${salesExportData.summary.totalItemsSold}`, 14, 46);
            doc.text(`Rata-rata Transaksi: ${formatRupiah(salesExportData.summary.averagePerTransaction)}`, 14, 52);
            doc.text(`Jumlah Transaksi: ${salesExportData.summary.totalTransactions}`, 150, 34);
            doc.text(`Penjualan Utang: ${formatRupiah(salesExportData.summary.creditSales)}`, 150, 40);
            doc.text(`Selisih Penjualan: ${salesExportData.summary.comparisonPeriodLabel ? formatRupiah(salesExportData.summary.salesDifferenceAmount) : '-'}`, 150, 46);

            doc.autoTable({
                startY: 60,
                head: [['No', 'Tanggal', 'ID Transaksi', 'Pembeli', 'Produk', 'Kategori', 'Jumlah', 'Total Harga', 'Status']],
                body: salesExportData.details.map((item, index) => [
                    index + 1,
                    item.date,
                    item.invoice,
                    item.customer,
                    item.product,
                    item.category,
                    item.quantity,
                    formatRupiah(item.total),
                    item.status
                ]),
                styles: { fontSize: 8 }
            });

            doc.addPage('a4', 'landscape');
            doc.setFontSize(14);
            doc.text('Produk Terlaris', 14, 14);
            doc.autoTable({
                startY: 22,
                head: [['No', 'Produk', 'Kategori', 'Jumlah Terjual', 'Omzet']],
                body: salesExportData.topProducts.map((item, index) => [
                    index + 1,
                    item.product,
                    item.category,
                    item.qty,
                    formatRupiah(item.total)
                ]),
                styles: { fontSize: 8 }
            });

            doc.save('laporan-penjualan.pdf');
        });
    }

    const chartCanvas = document.getElementById('salesChart');
    if (chartCanvas && salesExportData.chart.labels.length > 0) {
        new Chart(chartCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: salesExportData.chart.labels,
                datasets: [
                    {
                        label: 'Total Omzet',
                        data: salesExportData.chart.currentTotals,
                        backgroundColor: 'rgba(28, 77, 141, 0.82)',
                        borderColor: 'rgba(15, 40, 84, 1)',
                        borderWidth: 1,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 16,
                        right: 12,
                        bottom: 8,
                        left: 8
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Omzet Penjualan'
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.18)'
                        },
                        ticks: {
                            callback: function (value) {
                                return formatCompactRupiah(value);
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            usePointStyle: true,
                            boxWidth: 10,
                            boxHeight: 10
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.dataset.label}: ${formatRupiah(context.raw)}`;
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
