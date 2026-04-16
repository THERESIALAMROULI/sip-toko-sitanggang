@extends('layouts.admin')
@section('title', 'Laporan Stok')
@php
    $stockStatusSummary = collect([
        [
            'label' => 'Normal',
            'count' => $normalStockCount,
            'badge' => 'badge-green',
            'note' => 'Stok masih di atas batas minimum.',
        ],
        [
            'label' => 'Menipis',
            'count' => $lowStockCount,
            'badge' => 'badge-amber',
            'note' => 'Perlu dipantau dan disiapkan restok.',
        ],
        [
            'label' => 'Habis',
            'count' => $outStockCount,
            'badge' => 'badge-red',
            'note' => 'Barang tidak tersedia untuk dijual.',
        ],
    ]);
@endphp
@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div>
                <div class="card-title">Filter Laporan Stok</div>
            </div>
            <div class="td-actions">
                <button type="button" class="btn btn-secondary" id="exportStockExcel">Unduh Excel</button>
                <button type="button" class="btn btn-outline" id="exportStockPdf">Unduh PDF</button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.stock') }}" class="search-row">
                <input type="hidden" name="status" value="{{ $selectedStatus !== 'all' ? $selectedStatus : '' }}">
                <input type="text" name="q" class="search-input" placeholder="Cari nama produk..." value="{{ request('q') }}">
                <select name="kategori_id" class="filter-sel">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected((string) request('kategori_id') === (string) $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="{{ route('reports.stock', request()->except(['q', 'kategori_id', 'status', 'page'])) }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card sc-blue">
            <div class="sc-label">Total Produk</div>
            <div class="sc-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
            <div class="sc-sub">Semua data produk</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="sc-label">Menipis</div>
            <div class="sc-value">{{ number_format($lowStockCount, 0, ',', '.') }}</div>
            <div class="sc-sub">Perlu restok</div>
        </div>
        <div class="stat-card sc-red">
            <div class="sc-label">Stok Habis</div>
            <div class="sc-value">{{ number_format($outStockCount, 0, ',', '.') }}</div>
            <div class="sc-sub">Tidak tersedia</div>
        </div>
        <div class="stat-card sc-green">
            <div class="sc-label">Nilai Persediaan</div>
            <div class="sc-value mono">Rp {{ number_format($stockValue, 0, ',', '.') }}</div>
            <div class="sc-sub">Estimasi berdasarkan harga beli</div>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Distribusi Status Stok</div>
            <span class="badge badge-blue">Ringkasan cepat</span>
        </div>
        <div class="card-body">
            @if (array_sum($stockStatusChartValues) === 0)
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Belum ada data status stok untuk divisualisasikan.</p>
                </div>
            @else
                <div class="stock-report-chart-layout">
                    <div class="stock-report-chart-wrap">
                        <canvas id="stockStatusChart" height="170"></canvas>
                    </div>
                    <div class="stock-report-status-list">
                        @foreach ($stockStatusSummary as $item)
                            <div class="stock-report-status-item">
                                <div class="stock-report-status-main">
                                    <span class="badge {{ $item['badge'] }}">{{ $item['label'] }}</span>
                                    <div class="stock-report-status-count">{{ number_format($item['count'], 0, ',', '.') }} produk</div>
                                </div>
                                <div class="table-sub">{{ $item['note'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div>
                <div class="card-title">Detail Stok Produk</div>
            </div>
            <span class="badge badge-gray">{{ number_format($products->total(), 0, ',', '.') }} produk</span>
        </div>
        <div class="card-body stack-md">
            <div class="product-group-tabs">
                @foreach ($productGroups as $group)
                    <a
                        href="{{ route('reports.stock', array_merge(request()->except('page'), ['status' => $group['key'] === 'all' ? null : $group['key']])) }}"
                        class="btn {{ $selectedStatus === $group['key'] ? 'btn-primary' : 'btn-secondary' }}"
                    >
                        {{ $group['label'] }} ({{ number_format($group['count'], 0, ',', '.') }})
                    </a>
                @endforeach
            </div>

            @if ($products->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada produk pada kategori stok ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga Beli</th>
                            <th>Stok</th>
                            <th>Status Stok</th>
                            <th>Nilai Persediaan</th>
                            <th>Status Produk</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $products->firstItem() + $loop->index }}</td>
                                <td>{{ $product->nama }}</td>
                                <td>{{ $product->kategori->nama ?? '-' }}</td>
                                <td class="mono">Rp {{ number_format((int) $product->harga_beli, 0, ',', '.') }}</td>
                                <td class="mono">{{ number_format((int) $product->stok, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $product->stock_badge }}">{{ $product->stock_status_label }}</span>
                                    <div class="table-sub">Minimum {{ number_format((int) $product->stok_minimum, 0, ',', '.') }}</div>
                                </td>
                                <td class="mono">Rp {{ number_format((int) $product->stock_value, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $product->aktif ? 'badge-green' : 'badge-gray' }}">{{ $product->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($products->hasPages())
                    <div class="pagination-bar">
                        <div class="table-sub">
                            Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari {{ $products->total() }} produk
                        </div>
                        <div class="pagination-pages">
                            @if ($products->onFirstPage())
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                            @endif

                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page === $products->currentPage())
                                    <span class="btn btn-primary btn-sm" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="btn btn-secondary btn-sm">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="btn btn-secondary btn-sm">Berikutnya</a>
                            @else
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Berikutnya</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Riwayat Stok Masuk dari Supplier</div>
            <span class="badge badge-green">{{ number_format($incomingMutations->total(), 0, ',', '.') }} riwayat</span>
        </div>
        <div class="card-body">
            @if ($incomingMutations->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada riwayat stok masuk pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Sumber</th>
                            <th>Perubahan</th>
                            <th>Stok Sebelum</th>
                            <th>Stok Sesudah</th>
                            <th>Petugas</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($incomingMutations as $mutation)
                            <tr>
                                <td>{{ $incomingMutations->firstItem() + $loop->index }}</td>
                                <td>{{ optional($mutation->tanggal)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $mutation->produk->nama ?? '-' }}</td>
                                <td>{{ $mutation->supplier->nama ?? 'Manual' }}</td>
                                <td>
                                    <div class="mono">+{{ number_format((int) $mutation->jumlah, 0, ',', '.') }}</div>
                                    <div class="table-sub">Stok bertambah</div>
                                </td>
                                <td class="mono">{{ number_format((int) $mutation->stok_sebelum, 0, ',', '.') }}</td>
                                <td class="mono">{{ number_format((int) $mutation->stok_sesudah, 0, ',', '.') }}</td>
                                <td>{{ $mutation->user->name ?? '-' }}</td>
                                <td>{{ $mutation->keterangan ?: '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($incomingMutations->hasPages())
                    <div class="pagination-bar">
                        <div class="table-sub">
                            Menampilkan {{ $incomingMutations->firstItem() }}-{{ $incomingMutations->lastItem() }} dari {{ $incomingMutations->total() }} riwayat
                        </div>
                        <div class="pagination-pages">
                            @if ($incomingMutations->onFirstPage())
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $incomingMutations->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                            @endif

                            @foreach ($incomingMutations->getUrlRange(1, $incomingMutations->lastPage()) as $page => $url)
                                @if ($page === $incomingMutations->currentPage())
                                    <span class="btn btn-primary btn-sm" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="btn btn-secondary btn-sm">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($incomingMutations->hasMorePages())
                                <a href="{{ $incomingMutations->nextPageUrl() }}" class="btn btn-secondary btn-sm">Berikutnya</a>
                            @else
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Berikutnya</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Riwayat Stok Keluar dari Penjualan</div>
            <span class="badge badge-red">{{ number_format($salesOutgoingMutations->total(), 0, ',', '.') }} riwayat</span>
        </div>
        <div class="card-body">
            @if ($salesOutgoingMutations->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada riwayat stok keluar dari penjualan pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Sumber</th>
                            <th>Perubahan</th>
                            <th>Stok Sebelum</th>
                            <th>Stok Sesudah</th>
                            <th>Petugas</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($salesOutgoingMutations as $mutation)
                            <tr>
                                <td>{{ $salesOutgoingMutations->firstItem() + $loop->index }}</td>
                                <td>{{ optional($mutation->tanggal)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $mutation->produk->nama ?? '-' }}</td>
                                <td>Penjualan</td>
                                <td>
                                    <div class="mono">{{ number_format((int) $mutation->jumlah, 0, ',', '.') }}</div>
                                    <div class="table-sub">Stok berkurang</div>
                                </td>
                                <td class="mono">{{ number_format((int) $mutation->stok_sebelum, 0, ',', '.') }}</td>
                                <td class="mono">{{ number_format((int) $mutation->stok_sesudah, 0, ',', '.') }}</td>
                                <td>{{ $mutation->user->name ?? '-' }}</td>
                                <td>{{ $mutation->keterangan ?: '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($salesOutgoingMutations->hasPages())
                    <div class="pagination-bar">
                        <div class="table-sub">
                            Menampilkan {{ $salesOutgoingMutations->firstItem() }}-{{ $salesOutgoingMutations->lastItem() }} dari {{ $salesOutgoingMutations->total() }} riwayat
                        </div>
                        <div class="pagination-pages">
                            @if ($salesOutgoingMutations->onFirstPage())
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $salesOutgoingMutations->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                            @endif

                            @foreach ($salesOutgoingMutations->getUrlRange(1, $salesOutgoingMutations->lastPage()) as $page => $url)
                                @if ($page === $salesOutgoingMutations->currentPage())
                                    <span class="btn btn-primary btn-sm" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="btn btn-secondary btn-sm">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($salesOutgoingMutations->hasMorePages())
                                <a href="{{ $salesOutgoingMutations->nextPageUrl() }}" class="btn btn-secondary btn-sm">Berikutnya</a>
                            @else
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Berikutnya</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Riwayat Stok Keluar dari Supplier</div>
            <span class="badge badge-gray">{{ number_format($supplierOutgoingMutations->total(), 0, ',', '.') }} riwayat</span>
        </div>
        <div class="card-body">
            @if ($supplierOutgoingMutations->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada riwayat stok keluar non-penjualan pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Sumber</th>
                            <th>Perubahan</th>
                            <th>Stok Sebelum</th>
                            <th>Stok Sesudah</th>
                            <th>Petugas</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($supplierOutgoingMutations as $mutation)
                            <tr>
                                <td>{{ $supplierOutgoingMutations->firstItem() + $loop->index }}</td>
                                <td>{{ optional($mutation->tanggal)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $mutation->produk->nama ?? '-' }}</td>
                                <td>{{ $mutation->supplier->nama ?? 'Manual' }}</td>
                                <td>
                                    <div class="mono">{{ number_format((int) $mutation->jumlah, 0, ',', '.') }}</div>
                                    <div class="table-sub">Stok berkurang</div>
                                </td>
                                <td class="mono">{{ number_format((int) $mutation->stok_sebelum, 0, ',', '.') }}</td>
                                <td class="mono">{{ number_format((int) $mutation->stok_sesudah, 0, ',', '.') }}</td>
                                <td>{{ $mutation->user->name ?? '-' }}</td>
                                <td>{{ $mutation->keterangan ?: '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($supplierOutgoingMutations->hasPages())
                    <div class="pagination-bar">
                        <div class="table-sub">
                            Menampilkan {{ $supplierOutgoingMutations->firstItem() }}-{{ $supplierOutgoingMutations->lastItem() }} dari {{ $supplierOutgoingMutations->total() }} riwayat
                        </div>
                        <div class="pagination-pages">
                            @if ($supplierOutgoingMutations->onFirstPage())
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $supplierOutgoingMutations->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                            @endif

                            @foreach ($supplierOutgoingMutations->getUrlRange(1, $supplierOutgoingMutations->lastPage()) as $page => $url)
                                @if ($page === $supplierOutgoingMutations->currentPage())
                                    <span class="btn btn-primary btn-sm" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="btn btn-secondary btn-sm">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($supplierOutgoingMutations->hasMorePages())
                                <a href="{{ $supplierOutgoingMutations->nextPageUrl() }}" class="btn btn-secondary btn-sm">Berikutnya</a>
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
    const stockExportData = {
        products: {!! json_encode($exportProducts->map(function ($product) {
            $status = 'Normal';
            if (! $product->aktif) {
                $status = 'Nonaktif';
            } elseif ((int) $product->stok <= 0) {
                $status = 'Habis';
            } elseif ((int) $product->stok <= (int) $product->stok_minimum) {
                $status = 'Menipis';
            }

            return [
                'name' => $product->nama,
                'category' => $product->kategori->nama ?? '-',
                'stock' => (int) $product->stok,
                'min_stock' => (int) $product->stok_minimum,
                'purchase_price' => (int) $product->harga_beli,
                'stock_value' => ((int) $product->stok) * ((int) $product->harga_beli),
                'status' => $status,
                'product_status' => $product->aktif ? 'Aktif' : 'Nonaktif',
            ];
        })->values()) !!},
        incomingMutations: {!! json_encode($exportIncomingMutations->map(function ($mutation) {
            return [
                'date' => optional($mutation->tanggal)->format('d-m-Y H:i') ?? '-',
                'product' => $mutation->produk->nama ?? '-',
                'source' => $mutation->supplier->nama ?? 'Manual',
                'change' => '+'.number_format((int) $mutation->jumlah, 0, ',', '.'),
                'before' => (int) $mutation->stok_sebelum,
                'after' => (int) $mutation->stok_sesudah,
                'operator' => $mutation->user->name ?? '-',
                'note' => $mutation->keterangan ?? '-',
            ];
        })->values()) !!},
        salesOutgoingMutations: {!! json_encode($exportSalesOutgoingMutations->map(function ($mutation) {
            return [
                'date' => optional($mutation->tanggal)->format('d-m-Y H:i') ?? '-',
                'product' => $mutation->produk->nama ?? '-',
                'source' => 'Penjualan',
                'change' => number_format((int) $mutation->jumlah, 0, ',', '.'),
                'before' => (int) $mutation->stok_sebelum,
                'after' => (int) $mutation->stok_sesudah,
                'operator' => $mutation->user->name ?? '-',
                'note' => $mutation->keterangan ?? '-',
            ];
        })->values()) !!},
        supplierOutgoingMutations: {!! json_encode($exportSupplierOutgoingMutations->map(function ($mutation) {
            return [
                'date' => optional($mutation->tanggal)->format('d-m-Y H:i') ?? '-',
                'product' => $mutation->produk->nama ?? '-',
                'source' => $mutation->supplier->nama ?? 'Manual',
                'change' => number_format((int) $mutation->jumlah, 0, ',', '.'),
                'before' => (int) $mutation->stok_sebelum,
                'after' => (int) $mutation->stok_sesudah,
                'operator' => $mutation->user->name ?? '-',
                'note' => $mutation->keterangan ?? '-',
            ];
        })->values()) !!},
        summary: {
            totalProducts: {{ (int) $totalProducts }},
            normalStockCount: {{ (int) $normalStockCount }},
            lowStockCount: {{ (int) $lowStockCount }},
            outStockCount: {{ (int) $outStockCount }},
            stockValue: {{ (int) $stockValue }}
        },
        statusChart: {
            labels: {!! json_encode(['Normal', 'Menipis', 'Habis']) !!},
            values: {!! json_encode($stockStatusChartValues) !!}
        }
    };

    const formatRupiahStock = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;
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
    const buildMutationRows = (items) => items.map((item, index) => [
        index + 1,
        item.date,
        item.product,
        item.source,
        item.change,
        item.before,
        item.after,
        item.operator,
        item.note
    ]);

    const stockExcelBtn = document.getElementById('exportStockExcel');
    if (stockExcelBtn) {
        stockExcelBtn.addEventListener('click', () => {
            if (typeof XLSX === 'undefined') {
                window.alert('Modul Excel belum termuat. Silakan coba lagi.');
                return;
            }

            const rows = stockExportData.products.map((item, index) => [
                index + 1,
                item.name,
                item.category,
                item.stock,
                item.min_stock,
                item.purchase_price,
                item.stock_value,
                item.status,
                item.product_status
            ]);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(
                workbook,
                createExcelSheet(
                    'Laporan Stok',
                    ['No', 'Produk', 'Kategori', 'Stok', 'Stok Minimum', 'Harga Beli', 'Nilai Stok', 'Status Stok', 'Status Produk'],
                    rows,
                    [
                        ['Total Produk', stockExportData.summary.totalProducts],
                        ['Normal', stockExportData.summary.normalStockCount],
                        ['Menipis', stockExportData.summary.lowStockCount],
                        ['Stok Habis', stockExportData.summary.outStockCount],
                        ['Nilai Persediaan', stockExportData.summary.stockValue]
                    ]
                ),
                'Laporan Stok'
            );
            XLSX.utils.book_append_sheet(
                workbook,
                createExcelSheet(
                    'Riwayat Stok Masuk dari Supplier',
                    ['No', 'Tanggal', 'Produk', 'Sumber', 'Perubahan', 'Stok Sebelum', 'Stok Sesudah', 'Petugas', 'Keterangan'],
                    buildMutationRows(stockExportData.incomingMutations),
                    [['Jumlah Riwayat', stockExportData.incomingMutations.length]]
                ),
                'Stok Masuk'
            );
            XLSX.utils.book_append_sheet(
                workbook,
                createExcelSheet(
                    'Riwayat Stok Keluar dari Penjualan',
                    ['No', 'Tanggal', 'Produk', 'Sumber', 'Perubahan', 'Stok Sebelum', 'Stok Sesudah', 'Petugas', 'Keterangan'],
                    buildMutationRows(stockExportData.salesOutgoingMutations),
                    [['Jumlah Riwayat', stockExportData.salesOutgoingMutations.length]]
                ),
                'Keluar Penjualan'
            );
            XLSX.utils.book_append_sheet(
                workbook,
                createExcelSheet(
                    'Riwayat Stok Keluar dari Supplier',
                    ['No', 'Tanggal', 'Produk', 'Sumber', 'Perubahan', 'Stok Sebelum', 'Stok Sesudah', 'Petugas', 'Keterangan'],
                    buildMutationRows(stockExportData.supplierOutgoingMutations),
                    [['Jumlah Riwayat', stockExportData.supplierOutgoingMutations.length]]
                ),
                'Keluar Supplier'
            );
            XLSX.writeFile(workbook, 'laporan-stok.xlsx');
        });
    }

    const appendMutationPdfPage = (doc, title, items) => {
        doc.addPage('a4', 'landscape');
        doc.setFontSize(14);
        doc.text(title, 14, 14);
        doc.setFontSize(10);
        doc.text(`Jumlah Riwayat: ${items.length}`, 14, 22);
        doc.autoTable({
            startY: 30,
            head: [['No', 'Tanggal', 'Produk', 'Sumber', 'Perubahan', 'Stok Sebelum', 'Stok Sesudah', 'Petugas', 'Keterangan']],
            body: buildMutationRows(items),
            styles: { fontSize: 8 }
        });
    };

    const stockPdfBtn = document.getElementById('exportStockPdf');
    if (stockPdfBtn) {
        stockPdfBtn.addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape' });
            doc.setFontSize(14);
            doc.text('Laporan Stok', 14, 14);
            doc.setFontSize(10);
            doc.text(`Total Produk: ${stockExportData.summary.totalProducts}`, 14, 22);
            doc.text(`Normal: ${stockExportData.summary.normalStockCount}`, 14, 28);
            doc.text(`Menipis: ${stockExportData.summary.lowStockCount}`, 14, 34);
            doc.text(`Stok Habis: ${stockExportData.summary.outStockCount}`, 14, 40);
            doc.text(`Nilai Persediaan: ${formatRupiahStock(stockExportData.summary.stockValue)}`, 14, 46);
            doc.autoTable({
                startY: 54,
                head: [['No', 'Produk', 'Kategori', 'Stok', 'Stok Min', 'Harga Beli', 'Nilai Stok', 'Status Stok', 'Status Produk']],
                body: stockExportData.products.map((item, index) => [
                    index + 1,
                    item.name,
                    item.category,
                    item.stock,
                    item.min_stock,
                    formatRupiahStock(item.purchase_price),
                    formatRupiahStock(item.stock_value),
                    item.status,
                    item.product_status
                ]),
                styles: { fontSize: 8 }
            });
            appendMutationPdfPage(doc, 'Riwayat Stok Masuk dari Supplier', stockExportData.incomingMutations);
            appendMutationPdfPage(doc, 'Riwayat Stok Keluar dari Penjualan', stockExportData.salesOutgoingMutations);
            appendMutationPdfPage(doc, 'Riwayat Stok Keluar dari Supplier', stockExportData.supplierOutgoingMutations);
            doc.save('laporan-stok.pdf');
        });
    }

    const chartCanvas = document.getElementById('stockStatusChart');
    if (chartCanvas && stockExportData.statusChart.values.some((value) => Number(value) > 0)) {
        new Chart(chartCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: stockExportData.statusChart.labels,
                datasets: [{
                    data: stockExportData.statusChart.values,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.82)',
                        'rgba(245, 158, 11, 0.82)',
                        'rgba(239, 68, 68, 0.82)'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: ${Number(ctx.raw || 0).toLocaleString('id-ID')} produk`
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
