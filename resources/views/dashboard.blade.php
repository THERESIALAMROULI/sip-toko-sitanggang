@extends('layouts.admin')
@php
    $isDecisionRole = $role === 'owner';
    $isAdminDashboard = $role === 'admin';
@endphp
@section('title', 'Dashboard')
@section('subtitle', $isDecisionRole ? 'Ringkasan keputusan' : ($isAdminDashboard ? 'Ringkasan admin' : 'Ringkasan hari ini'))
@section('content')
@if ($isDecisionRole)
    <div class="alert alert-info">
        Lihat prioritas stok, penjualan, dan piutang dari {{ number_format($totalProducts, 0, ',', '.') }} produk aktif.
    </div>

    <div class="stat-grid">
        <div class="stat-card sc-blue">
            <div class="sc-label">Periode Teramai</div>
            <div class="sc-value sc-compact">{{ $peakMonthLabel ?? '-' }}</div>
            <div class="sc-sub">
                @if ($peakMonth)
                    Rp {{ number_format($peakMonth->total_sales, 0, ',', '.') }} dari {{ number_format($peakMonth->transaction_count, 0, ',', '.') }} transaksi
                @else
                    Belum ada transaksi untuk dianalisis
                @endif
            </div>
        </div>
        <div class="stat-card sc-green">
            <div class="sc-label">Hari Paling Ramai</div>
            <div class="sc-value sc-compact">
                {{ $peakDay ? \Carbon\Carbon::parse($peakDay->day_key)->translatedFormat('d M Y') : '-' }}
            </div>
            <div class="sc-sub">
                @if ($peakDay)
                    {{ number_format($peakDay->transaction_count, 0, ',', '.') }} transaksi dengan omzet Rp {{ number_format($peakDay->total_sales, 0, ',', '.') }}
                @else
                    Belum ada data puncak harian
                @endif
            </div>
        </div>
        <div class="stat-card sc-amber">
            <div class="sc-label">Produk Kunci Saat Puncak</div>
            <div class="sc-value sc-compact">{{ $peakMonthLeader->product_name ?? '-' }}</div>
            <div class="sc-sub">
                @if ($peakMonthLeader)
                    {{ number_format($peakMonthLeader->qty_sold, 0, ',', '.') }} item terjual pada {{ $peakMonthLabel }}
                @else
                    Belum ada produk unggulan
                @endif
            </div>
        </div>
        <div class="stat-card sc-red">
            <div class="sc-label">Prioritas Restok</div>
            <div class="sc-value">{{ number_format($restockPriorityCount, 0, ',', '.') }}</div>
            <div class="sc-sub">{{ number_format($slowMoverCount, 0, ',', '.') }} produk lambat laku dalam 30 hari</div>
        </div>
    </div>

    <div class="grid-2 mb-4">
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Musim Ramai Toko</div>
                <span class="badge badge-blue">12 bulan terakhir</span>
            </div>
            <div class="card-body">
                @if ($peakMonth || $peakDay)
                    <div class="insight-list">
                        <div class="insight-item">
                            <div class="insight-eyebrow">Periode omzet tertinggi</div>
                            <div class="insight-main">{{ $peakMonthLabel ?? '-' }}</div>
                            <div class="insight-sub">
                                @if ($peakMonth)
                                    Rp {{ number_format($peakMonth->total_sales, 0, ',', '.') }} dari {{ number_format($peakMonth->transaction_count, 0, ',', '.') }} transaksi.
                                    @if ($peakMonthLeader)
                                        Produk paling laris: {{ $peakMonthLeader->product_name }} ({{ number_format($peakMonthLeader->qty_sold, 0, ',', '.') }} item).
                                    @endif
                                @else
                                    Data periode puncak belum tersedia.
                                @endif
                            </div>
                        </div>
                        <div class="insight-item">
                            <div class="insight-eyebrow">Hari paling sibuk</div>
                            <div class="insight-main">{{ $peakDayLabel ?? '-' }}</div>
                            <div class="insight-sub">
                                @if ($peakDay)
                                    {{ number_format($peakDay->transaction_count, 0, ',', '.') }} transaksi dan omzet Rp {{ number_format($peakDay->total_sales, 0, ',', '.') }}.
                                    @if ($peakDayLeader)
                                        Produk terlaris hari itu: {{ $peakDayLeader->product_name }}.
                                    @endif
                                @else
                                    Data puncak harian belum tersedia.
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Belum ada data penjualan.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-hd">
                <div class="card-title">Arah Tindakan</div>
                <span class="badge badge-amber">{{ $decisionWindowLabel }}</span>
            </div>
            <div class="card-body">
                @if ($decisionHighlights->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Belum ada ringkasan prioritas.</p>
                    </div>
                @else
                    <div class="stack-sm">
                        @foreach ($decisionHighlights as $highlight)
                            <div class="decision-note">
                                <div class="decision-note-title">{{ $highlight['title'] }}</div>
                                <div class="decision-note-text">{{ $highlight['text'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid-2 mb-4">
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Tren Penjualan</div>
                <span class="badge badge-gray">12 bulan</span>
            </div>
            <div class="card-body">
                <canvas id="salesTrendChart" height="120"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-hd">
                <div class="card-title">
                    Top Produk Saat {{ $peakMonthLabel ?? 'Periode Puncak' }}
                </div>
            </div>
            <div class="card-body">
                @if ($peakMonthTopProducts->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Belum ada produk terlaris pada periode puncak.</p>
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
                            @foreach ($peakMonthTopProducts as $item)
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
    </div>

    <div class="grid-2 mb-4">
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Prioritas Restok</div>
                <span class="badge badge-red">30 hari terakhir</span>
            </div>
            <div class="card-body">
                @if ($restockPriorities->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Belum ada produk prioritas restok.</p>
                    </div>
                @else
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Terjual</th>
                                <th>Daya Tahan</th>
                                <th>Saran Restok</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($restockPriorities as $product)
                                <tr>
                                    <td>
                                        <div>{{ $product->nama }}</div>
                                        <div class="table-sub">{{ $product->category_name ?? '-' }}</div>
                                    </td>
                                    <td class="mono">{{ number_format($product->stok, 0, ',', '.') }}</td>
                                    <td class="mono">{{ number_format($product->qty_sold_30, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($product->stock_cover_days !== null)
                                            <span class="badge {{ $product->stock_cover_days <= 7 ? 'badge-red' : 'badge-amber' }}">
                                                {{ rtrim(rtrim(number_format($product->stock_cover_days, 1, ',', '.'), '0'), ',') }} hari
                                            </span>
                                        @else
                                            <span class="badge badge-gray">Belum terbaca</span>
                                        @endif
                                    </td>
                                    <td class="mono">{{ number_format($product->suggested_restock, 0, ',', '.') }}</td>
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
                <div class="card-title">Produk Lambat Laku</div>
                <span class="badge badge-gray">Tidak terjual 30 hari</span>
            </div>
            <div class="card-body">
                @if ($slowMovingProducts->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Belum ada produk lambat laku.</p>
                    </div>
                @else
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Terakhir Laku</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($slowMovingProducts->take(8) as $product)
                                <tr>
                                    <td>
                                        <div>{{ $product->nama }}</div>
                                        <div class="table-sub">{{ $product->category_name ?? '-' }}</div>
                                    </td>
                                    <td class="mono">{{ number_format($product->stok, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($product->days_since_last_sale !== null)
                                            {{ number_format($product->days_since_last_sale, 0, ',', '.') }} hari lalu
                                        @else
                                            Belum pernah terjual
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

    <div class="grid-2">
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Kategori Penggerak Omzet</div>
                <span class="badge badge-blue">{{ $decisionWindowLabel }}</span>
            </div>
            <div class="card-body stack-md">
                @if ($categoryLeaders->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Belum ada kategori utama.</p>
                    </div>
                @else
                    <canvas id="categorySalesChart" height="110"></canvas>
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($categoryLeaders as $category)
                                <tr>
                                    <td>{{ $category->category_name }}</td>
                                    <td class="mono">{{ number_format($category->qty_sold, 0, ',', '.') }}</td>
                                    <td class="mono">Rp {{ number_format($category->total_sales, 0, ',', '.') }}</td>
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
                <div class="card-title">Piutang Risiko Tinggi</div>
                <span class="badge badge-red">Rp {{ number_format($overdueReceivablesAmount, 0, ',', '.') }}</span>
            </div>
            <div class="card-body">
                @if ($overdueReceivables->isEmpty())
                    <div class="empty-state">
                        <div class="es-icon">-</div>
                        <p>Tidak ada piutang yang lewat jatuh tempo.</p>
                    </div>
                @else
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Transaksi</th>
                                <th>Jatuh Tempo</th>
                                <th>Jumlah</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($overdueReceivables as $receivable)
                                <tr>
                                    <td>{{ $receivable->transaction->customer->name ?? '-' }}</td>
                                    <td>#{{ $receivable->transaction_id }}</td>
                                    <td>{{ optional($receivable->due_date)->format('d-m-Y') ?? '-' }}</td>
                                    <td class="mono">Rp {{ number_format($receivable->amount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-sub mt-2">{{ number_format($overdueReceivablesCount, 0, ',', '.') }} piutang perlu ditindaklanjuti.</div>
                @endif
            </div>
        </div>
    </div>
@elseif ($isAdminDashboard)
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}" class="dashboard-filter">
                <div class="field">
                    <label for="scope">Tampilan</label>
                    <select id="scope" name="scope" class="filter-sel">
                        <option value="day" @selected($dashboardScope === 'day')>Harian</option>
                        <option value="week" @selected($dashboardScope === 'week')>Mingguan</option>
                        <option value="month" @selected($dashboardScope === 'month')>Bulanan</option>
                    </select>
                </div>
                <div class="field">
                    <label for="start_date">Dari Tanggal</label>
                    <input id="start_date" type="date" name="start_date" value="{{ $dashboardStartDate }}">
                    @error('start_date')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="end_date">Sampai Tanggal</label>
                    <input id="end_date" type="date" name="end_date" value="{{ $dashboardEndDate }}">
                    @error('end_date')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="td-actions dashboard-filter-actions">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Reset</a>
                </div>
                <div class="dashboard-filter-note">
                    {{ $scopeOptionLabel }} ({{ $scopeRangeLabel }})
                </div>
            </form>
        </div>
    </div>

    <div class="card admin-focus-card mb-4">
        <div class="card-body">
            <div class="admin-focus-head">
                <div>
                    <div class="card-title">Fokus Admin</div>
                    <div class="table-sub">Prioritas utama pada periode {{ $scopeRangeLabel }}.</div>
                </div>
                <span class="badge badge-blue">{{ $scopeOptionLabel }}</span>
            </div>
            <div class="admin-focus-grid">
                @foreach ($adminHighlights as $highlight)
                    <button
                        type="button"
                        class="admin-focus-item tone-{{ $highlight['tone'] ?? 'blue' }}"
                        data-admin-focus-button
                        data-target="{{ $highlight['target'] ?? '' }}"
                        data-label="{{ $highlight['title'] }}"
                        data-metric="{{ $highlight['metric'] ?? '' }}"
                    >
                        <div class="admin-focus-label">{{ $highlight['title'] }}</div>
                        <div class="admin-focus-value">{{ $highlight['metric'] }}</div>
                        <div class="admin-focus-text">{{ $highlight['text'] }}</div>
                        <div class="admin-focus-cta">Lihat detail</div>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-hd">
            <div class="card-title">Detail Fokus Admin</div>
            <span class="badge badge-gray" id="adminFocusDetailBadge">Pilih salah satu kartu</span>
        </div>
        <div class="card-body">
            <div id="adminFocusPlaceholder" class="empty-state admin-focus-empty">
                <div class="es-icon">-</div>
                <p>Pilih satu kartu untuk lihat detail.</p>
            </div>

            <div class="admin-focus-panel" data-admin-focus-panel="restock" hidden>
                @if ($restockPriorities->isEmpty())
                    <div class="empty-state admin-focus-empty">
                        <div class="es-icon">-</div>
                        <p>Belum ada produk untuk restok.</p>
                    </div>
                @else
                    @php $restockPages = $restockPriorities->chunk(10); @endphp
                    <div class="table-sub mb-2" data-admin-page-summary data-total="{{ $restockPriorityCount }}" data-label="produk">
                        Menampilkan <span data-admin-page-start>1</span>-<span data-admin-page-end>{{ min(10, $restockPriorityCount) }}</span> dari {{ $restockPriorityCount }} produk.
                    </div>
                    @foreach ($restockPages as $pageIndex => $productsPage)
                        <div class="admin-item-list admin-item-page" data-admin-page="{{ $pageIndex + 1 }}" @if ($pageIndex > 0) hidden @endif>
                            @foreach ($productsPage as $product)
                                <div class="admin-item-row">
                                    <div class="admin-item-main">
                                        <div class="admin-item-title">{{ $product->nama }}</div>
                                        <div class="table-sub">{{ $product->category_name ?? '-' }}</div>
                                    </div>
                                    <div class="admin-item-meta">
                                        <span class="badge badge-red">Stok {{ number_format($product->stok, 0, ',', '.') }}</span>
                                        <span class="badge badge-amber">Saran {{ number_format($product->suggested_restock, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    @if ($restockPages->count() > 1)
                        <div class="admin-panel-footer">
                            <div class="table-sub" data-admin-page-status>Halaman 1 dari {{ $restockPages->count() }}</div>
                            <div class="admin-panel-nav">
                                <button type="button" class="btn btn-secondary btn-sm" data-admin-page-prev disabled>Sebelumnya</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-admin-page-next>Selanjutnya</button>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="admin-focus-panel" data-admin-focus-panel="slow" hidden>
                @if ($slowMovingProducts->isEmpty())
                    <div class="empty-state admin-focus-empty">
                        <div class="es-icon">-</div>
                        <p>Belum ada produk sepi.</p>
                    </div>
                @else
                    @php $slowPages = $slowMovingProducts->chunk(10); @endphp
                    <div class="table-sub mb-2" data-admin-page-summary data-total="{{ $slowMoverCount }}" data-label="produk">
                        Menampilkan <span data-admin-page-start>1</span>-<span data-admin-page-end>{{ min(10, $slowMoverCount) }}</span> dari {{ $slowMoverCount }} produk.
                    </div>
                    @foreach ($slowPages as $pageIndex => $productsPage)
                        <div class="admin-item-list admin-item-page" data-admin-page="{{ $pageIndex + 1 }}" @if ($pageIndex > 0) hidden @endif>
                            @foreach ($productsPage as $product)
                                <div class="admin-item-row">
                                    <div class="admin-item-main">
                                        <div class="admin-item-title">{{ $product->nama }}</div>
                                        <div class="table-sub">{{ $product->category_name ?? '-' }}</div>
                                    </div>
                                    <div class="admin-item-meta">
                                        <span class="badge badge-gray">Stok {{ number_format($product->stok, 0, ',', '.') }}</span>
                                        <span class="badge badge-amber">
                                            @if ($product->days_since_last_sale !== null)
                                                {{ number_format($product->days_since_last_sale, 0, ',', '.') }} hari lalu
                                            @else
                                                Belum pernah terjual
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    @if ($slowPages->count() > 1)
                        <div class="admin-panel-footer">
                            <div class="table-sub" data-admin-page-status>Halaman 1 dari {{ $slowPages->count() }}</div>
                            <div class="admin-panel-nav">
                                <button type="button" class="btn btn-secondary btn-sm" data-admin-page-prev disabled>Sebelumnya</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-admin-page-next>Selanjutnya</button>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="admin-focus-panel" data-admin-focus-panel="category" hidden>
                @if ($adminCategoryCheckProducts->isEmpty())
                    <div class="empty-state admin-focus-empty">
                        <div class="es-icon">-</div>
                        <p>Belum ada produk yang perlu dicek.</p>
                    </div>
                @else
                    @php $categoryPages = $adminCategoryCheckProducts->chunk(10); @endphp
                    <div class="table-sub mb-2" data-admin-page-summary data-total="{{ $adminCategoryCheckProducts->count() }}" data-label="produk">
                        Menampilkan <span data-admin-page-start>1</span>-<span data-admin-page-end>{{ min(10, $adminCategoryCheckProducts->count()) }}</span> dari {{ $adminCategoryCheckProducts->count() }} produk.
                    </div>
                    @foreach ($categoryPages as $pageIndex => $productsPage)
                        <div class="admin-item-list admin-item-page" data-admin-page="{{ $pageIndex + 1 }}" @if ($pageIndex > 0) hidden @endif>
                            @foreach ($productsPage as $product)
                                @php
                                    $statusLabel = (int) $product->stok <= 0 ? 'Habis' : 'Menipis';
                                    $statusBadge = (int) $product->stok <= 0 ? 'badge-red' : 'badge-amber';
                                @endphp
                                <div class="admin-item-row">
                                    <div class="admin-item-main">
                                        <div class="admin-item-title">{{ $product->nama }}</div>
                                        <div class="table-sub">{{ $product->category_name ?? '-' }}</div>
                                    </div>
                                    <div class="admin-item-meta">
                                        <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                                        <span class="badge badge-gray">Stok {{ number_format($product->stok, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    @if ($categoryPages->count() > 1)
                        <div class="admin-panel-footer">
                            <div class="table-sub" data-admin-page-status>Halaman 1 dari {{ $categoryPages->count() }}</div>
                            <div class="admin-panel-nav">
                                <button type="button" class="btn btn-secondary btn-sm" data-admin-page-prev disabled>Sebelumnya</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-admin-page-next>Selanjutnya</button>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

    <div class="grid-2 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="admin-summary-label">Penjualan</div>
                <div class="admin-summary-value mono">Rp {{ number_format($periodSalesTotal, 0, ',', '.') }}</div>
                <div class="admin-summary-text">{{ number_format($periodTransactionCount, 0, ',', '.') }} transaksi</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="admin-summary-label">Biaya Operasional</div>
                <div class="admin-summary-value mono">Rp {{ number_format($periodExpenseTotal, 0, ',', '.') }}</div>
                <div class="admin-summary-text">Total pengeluaran</div>
            </div>
        </div>
    </div>

    <div class="grid-2 mb-4">
        <div class="card">
            <div class="card-hd">
                <div class="card-title">Penjualan per Periode</div>
            </div>
            <div class="card-body">
                <canvas id="adminSalesChart" height="120"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-hd">
                <div class="card-title">Kondisi Barang Saat Ini</div>
            </div>
            <div class="card-body">
                <canvas id="adminStockHealthChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-hd">
                <div class="card-title">Daftar Restok</div>
        </div>
        <div class="card-body">
            <canvas id="adminRestockChart" height="120"></canvas>
        </div>
    </div>

@else
    <div class="stat-grid">
        <div class="stat-card sc-green">
            <div class="sc-label">Penjualan Hari Ini</div>
            <div class="sc-value mono">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
            <div class="sc-sub">{{ $todayTransactionCount }} transaksi hari ini</div>
        </div>
        <div class="stat-card sc-blue">
            <div class="sc-label">Produk Aktif</div>
            <div class="sc-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
            <div class="sc-sub">Barang siap jual saat ini</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="sc-label">Total Pelanggan</div>
            <div class="sc-value">{{ number_format($totalCustomers, 0, ',', '.') }}</div>
            <div class="sc-sub">Pelanggan terdaftar</div>
        </div>
        <div class="stat-card sc-red">
            <div class="sc-label">Piutang Berjalan</div>
            <div class="sc-value mono">Rp {{ number_format($outstandingReceivables, 0, ',', '.') }}</div>
            <div class="sc-sub">{{ number_format($overdueReceivablesCount, 0, ',', '.') }} lewat jatuh tempo</div>
        </div>
    </div>

    <div class="grid-2">
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
                        <p>Belum ada stok kritis.</p>
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
@endif
@endsection

@if ($isDecisionRole || $isAdminDashboard)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const formatRupiah = (value) => 'Rp ' + Number(value || 0).toLocaleString('id-ID');
            const createChart = (elementId, config) => {
                const canvas = document.getElementById(elementId);
                if (!canvas) {
                    return;
                }

                new Chart(canvas.getContext('2d'), config);
            };

            createChart('adminSalesChart', {
                type: 'line',
                data: {
                    labels: {!! json_encode($adminSalesChartLabels) !!},
                    datasets: [{
                        label: 'Penjualan',
                        data: {!! json_encode($adminSalesChartValues) !!},
                        borderColor: 'rgba(28, 77, 141, 1)',
                        backgroundColor: 'rgba(73, 136, 196, 0.14)',
                        fill: true,
                        tension: 0.32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => formatRupiah(value)
                            }
                        }
                    }
                }
            });

            createChart('adminStockHealthChart', {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($stockHealthChartLabels) !!},
                    datasets: [{
                        data: {!! json_encode($stockHealthChartValues) !!},
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.82)',
                            'rgba(245, 158, 11, 0.82)',
                            'rgba(16, 185, 129, 0.82)',
                            'rgba(28, 77, 141, 0.82)'
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            createChart('adminRestockChart', {
                type: 'bar',
                data: {
                    labels: {!! json_encode($restockChartLabels) !!},
                    datasets: [{
                        label: 'Saran Restok',
                        data: {!! json_encode($restockChartValues) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.78)',
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const adminFocusButtons = document.querySelectorAll('[data-admin-focus-button]');
            const adminFocusPanels = document.querySelectorAll('[data-admin-focus-panel]');
            const adminFocusPlaceholder = document.getElementById('adminFocusPlaceholder');
            const adminFocusDetailBadge = document.getElementById('adminFocusDetailBadge');

            if (adminFocusButtons.length && adminFocusPanels.length && adminFocusPlaceholder && adminFocusDetailBadge) {
                const setupAdminFocusPanel = (panel) => {
                    const pages = Array.from(panel.querySelectorAll('[data-admin-page]'));
                    const summary = panel.querySelector('[data-admin-page-summary]');
                    const startNode = panel.querySelector('[data-admin-page-start]');
                    const endNode = panel.querySelector('[data-admin-page-end]');
                    const statusNode = panel.querySelector('[data-admin-page-status]');
                    const prevButton = panel.querySelector('[data-admin-page-prev]');
                    const nextButton = panel.querySelector('[data-admin-page-next]');
                    const total = Number(summary?.dataset.total || pages.length);
                    const pageSize = 10;

                    if (!pages.length) {
                        return;
                    }

                    const setPage = (pageNumber) => {
                        const pageCount = pages.length;
                        const safePage = Math.min(Math.max(pageNumber, 1), pageCount);

                        pages.forEach((page, index) => {
                            page.hidden = index !== safePage - 1;
                        });

                        if (startNode && endNode) {
                            const start = total === 0 ? 0 : ((safePage - 1) * pageSize) + 1;
                            const end = Math.min(safePage * pageSize, total);
                            startNode.textContent = `${start}`;
                            endNode.textContent = `${end}`;
                        }

                        if (statusNode) {
                            statusNode.textContent = `Halaman ${safePage} dari ${pageCount}`;
                        }

                        if (prevButton) {
                            prevButton.disabled = safePage === 1;
                        }

                        if (nextButton) {
                            nextButton.disabled = safePage === pageCount;
                        }

                        panel.dataset.currentPage = `${safePage}`;
                    };

                    if (prevButton) {
                        prevButton.addEventListener('click', () => {
                            const currentPage = Number(panel.dataset.currentPage || 1);
                            setPage(currentPage - 1);
                        });
                    }

                    if (nextButton) {
                        nextButton.addEventListener('click', () => {
                            const currentPage = Number(panel.dataset.currentPage || 1);
                            setPage(currentPage + 1);
                        });
                    }

                    panel.__setAdminPage = setPage;
                    setPage(1);
                };

                adminFocusPanels.forEach((panel) => setupAdminFocusPanel(panel));

                adminFocusButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        const target = button.dataset.target;
                        const label = button.dataset.label || 'Pilih salah satu kartu';
                        const metric = button.dataset.metric || '';
                        const currentPanel = document.querySelector(`[data-admin-focus-panel="${target}"]`);
                        const isActive = button.classList.contains('is-active');

                        adminFocusButtons.forEach((item) => item.classList.remove('is-active'));
                        adminFocusPanels.forEach((panel) => {
                            panel.hidden = true;
                        });

                        if (isActive || !currentPanel) {
                            adminFocusPlaceholder.hidden = false;
                            adminFocusDetailBadge.textContent = 'Pilih salah satu kartu';
                            return;
                        }

                        button.classList.add('is-active');
                        currentPanel.hidden = false;
                        adminFocusPlaceholder.hidden = true;
                        if (typeof currentPanel.__setAdminPage === 'function') {
                            currentPanel.__setAdminPage(1);
                        }
                        adminFocusDetailBadge.textContent = metric ? `${label} - ${metric}` : label;
                    });
                });
            }

            createChart('salesTrendChart', {
                type: 'line',
                data: {
                    labels: {!! json_encode($salesTrendLabels) !!},
                    datasets: [{
                        label: 'Omzet Penjualan',
                        data: {!! json_encode($salesTrendValues) !!},
                        borderColor: 'rgba(28, 77, 141, 1)',
                        backgroundColor: 'rgba(73, 136, 196, 0.14)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => formatRupiah(value)
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            createChart('categorySalesChart', {
                type: 'bar',
                data: {
                    labels: {!! json_encode($categoryChartLabels) !!},
                    datasets: [{
                        label: 'Total Penjualan',
                        data: {!! json_encode($categoryChartValues) !!},
                        backgroundColor: [
                            'rgba(28, 77, 141, 0.78)',
                            'rgba(73, 136, 196, 0.78)',
                            'rgba(16, 185, 129, 0.78)',
                            'rgba(245, 158, 11, 0.78)',
                            'rgba(239, 68, 68, 0.78)'
                        ],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => formatRupiah(value)
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        })();
    </script>
    @endpush
@endif
