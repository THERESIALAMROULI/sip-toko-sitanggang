@extends('layouts.admin')
@section('title', 'Laporan Stok')
@section('subtitle', 'Ringkasan persediaan dan mutasi stok')
@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Laporan Stok</div>
            <div class="td-actions">
                <button type="button" class="btn btn-secondary" id="exportStockExcel">Unduh Excel</button>
                <button type="button" class="btn btn-outline" id="exportStockPdf">Unduh PDF</button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.stock') }}" class="search-row">
                <input type="text" name="q" class="search-input" placeholder="Cari nama produk..." value="{{ request('q') }}">
                <select name="kategori_id" class="filter-sel">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected((string) request('kategori_id') === (string) $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
                <select name="status" class="filter-sel">
                    <option value="">Semua Status</option>
                    <option value="normal" @selected(request('status') === 'normal')>Normal</option>
                    <option value="low" @selected(request('status') === 'low')>Hampir Habis</option>
                    <option value="out" @selected(request('status') === 'out')>Habis</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="{{ route('reports.stock') }}" class="btn btn-secondary">Reset</a>
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
            <div class="sc-label">Hampir Habis</div>
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
    <div class="grid-2">
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Detail Stok Produk</div>
            </div>
            <div class="card-body">
                @if ($products->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Tidak ada produk sesuai filter.</p>
                    </div>
                @else
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Min</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($products as $product)
                                @php
                                    $status = 'Normal';
                                    $badge = 'badge-green';
                                    if (! $product->aktif) {
                                        $status = 'Nonaktif';
                                        $badge = 'badge-gray';
                                    } elseif ((int) $product->stok <= 0) {
                                        $status = 'Habis';
                                        $badge = 'badge-red';
                                    } elseif ((int) $product->stok <= (int) $product->stok_minimum) {
                                        $status = 'Hampir Habis';
                                        $badge = 'badge-amber';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $product->nama }}</td>
                                    <td>{{ $product->kategori->nama ?? '-' }}</td>
                                    <td class="mono">{{ $product->stok }}</td>
                                    <td class="mono">{{ $product->stok_minimum }}</td>
                                    <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
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
                <div class="card-title">Mutasi Stok Terbaru</div>
            </div>
            <div class="card-body">
                @if ($recentMutations->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Belum ada mutasi stok.</p>
                    </div>
                @else
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Petugas</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($recentMutations as $mutation)
                                <tr>
                                    <td>{{ optional($mutation->tanggal)->format('d-m-Y H:i') ?? '-' }}</td>
                                    <td>{{ $mutation->produk->nama ?? '-' }}</td>
                                    <td class="mono">{{ $mutation->jumlah > 0 ? '+' : '' }}{{ $mutation->jumlah }}</td>
                                    <td>{{ $mutation->user->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    const stockExportData = {
        products: {!! json_encode($products->map(function ($product) {
            $status = 'Normal';
            if (! $product->aktif) {
                $status = 'Nonaktif';
            } elseif ((int) $product->stok <= 0) {
                $status = 'Habis';
            } elseif ((int) $product->stok <= (int) $product->stok_minimum) {
                $status = 'Hampir Habis';
            }
            return [
                'name' => $product->nama,
                'category' => $product->kategori->nama ?? '-',
                'stock' => (int) $product->stok,
                'min_stock' => (int) $product->stok_minimum,
                'status' => $status,
            ];
        })->values()) !!},
        summary: {
            totalProducts: {{ (int) $totalProducts }},
            lowStockCount: {{ (int) $lowStockCount }},
            outStockCount: {{ (int) $outStockCount }},
            stockValue: {{ (int) $stockValue }}
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
                item.status
            ]);
            const worksheet = createExcelSheet(
                'Laporan Stok',
                ['No', 'Produk', 'Kategori', 'Stok', 'Stok Minimum', 'Status'],
                rows,
                [
                    ['Total Produk', stockExportData.summary.totalProducts],
                    ['Hampir Habis', stockExportData.summary.lowStockCount],
                    ['Stok Habis', stockExportData.summary.outStockCount],
                    ['Nilai Persediaan', stockExportData.summary.stockValue]
                ]
            );
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Stok');
            XLSX.writeFile(workbook, 'laporan-stok.xlsx');
        });
    }
    const stockPdfBtn = document.getElementById('exportStockPdf');
    if (stockPdfBtn) {
        stockPdfBtn.addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape' });
            doc.setFontSize(14);
            doc.text('Laporan Stok', 14, 14);
            doc.setFontSize(10);
            doc.text(`Total Produk: ${stockExportData.summary.totalProducts}`, 14, 22);
            doc.text(`Hampir Habis: ${stockExportData.summary.lowStockCount}`, 14, 28);
            doc.text(`Stok Habis: ${stockExportData.summary.outStockCount}`, 14, 34);
            doc.text(`Nilai Persediaan: ${formatRupiahStock(stockExportData.summary.stockValue)}`, 14, 40);
            doc.autoTable({
                startY: 48,
                head: [['No', 'Produk', 'Kategori', 'Stok', 'Stok Min', 'Status']],
                body: stockExportData.products.map((item, index) => [
                    index + 1,
                    item.name,
                    item.category,
                    item.stock,
                    item.min_stock,
                    item.status
                ]),
                styles: { fontSize: 8 }
            });
            doc.save('laporan-stok.pdf');
        });
    }
</script>
@endpush
