@extends('layouts.admin')
@section('title', 'Manajemen Stok')
@section('subtitle', 'Riwayat stok')
@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Riwayat Stok</div>
            @if ((Auth::user()->role ?? null) === 'admin')
                <a href="{{ route('stok_histories.create') }}" class="btn btn-primary">+ Tambah Riwayat Stok</a>
            @endif
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('stok_histories.index') }}" class="search-row">
                <select name="produk_id" class="filter-sel">
                    <option value="">Semua Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected((int) ($filters['produk_id'] ?? 0) === $product->id)>{{ $product->nama }}</option>
                    @endforeach
                </select>
                <select name="supplier_id" class="filter-sel">
                    <option value="">Semua Pemasok</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected((int) ($filters['supplier_id'] ?? 0) === $supplier->id)>{{ $supplier->nama }}</option>
                    @endforeach
                </select>
                <input type="date" name="start_date" class="search-input" value="{{ $filters['start_date'] ?? '' }}">
                <input type="date" name="end_date" class="search-input" value="{{ $filters['end_date'] ?? '' }}">
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="{{ route('stok_histories.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Riwayat Stok Masuk dari Supplier</div>
        </div>
        <div class="card-body">
            @if ($incomingSupplierHistories->isEmpty())
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
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($incomingSupplierHistories as $history)
                            <tr>
                                <td>{{ $incomingSupplierHistories->firstItem() + $loop->index }}</td>
                                <td>{{ optional($history->tanggal)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $history->produk->nama ?? '-' }}</td>
                                <td>{{ $history->supplier->nama ?? 'Manual' }}</td>
                                <td>
                                    <div class="mono">+{{ number_format($history->jumlah, 0, ',', '.') }}</div>
                                    <div class="table-sub">Stok bertambah</div>
                                </td>
                                <td>{{ $history->stok_sebelum }}</td>
                                <td>{{ $history->stok_sesudah }}</td>
                                <td>{{ $history->user->name ?? '-' }}</td>
                                <td>{{ $history->keterangan ?: '-' }}</td>
                                <td>
                                    <div class="td-actions">
                                        <a href="{{ route('stok_histories.correction', $history->id) }}" class="btn btn-secondary btn-sm">Buat Koreksi</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($incomingSupplierHistories->hasPages())
                    <div class="pagination-bar">
                        <div class="table-sub">
                            Menampilkan {{ $incomingSupplierHistories->firstItem() }}-{{ $incomingSupplierHistories->lastItem() }} dari {{ $incomingSupplierHistories->total() }} riwayat
                        </div>
                        <div class="pagination-pages">
                            @if ($incomingSupplierHistories->onFirstPage())
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $incomingSupplierHistories->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                            @endif

                            @foreach ($incomingSupplierHistories->getUrlRange(1, $incomingSupplierHistories->lastPage()) as $page => $url)
                                @if ($page === $incomingSupplierHistories->currentPage())
                                    <span class="btn btn-primary btn-sm" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="btn btn-secondary btn-sm">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($incomingSupplierHistories->hasMorePages())
                                <a href="{{ $incomingSupplierHistories->nextPageUrl() }}" class="btn btn-secondary btn-sm">Berikutnya</a>
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
        </div>
        <div class="card-body">
            @if ($salesOutgoingHistories->isEmpty())
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
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($salesOutgoingHistories as $history)
                            <tr>
                                <td>{{ $salesOutgoingHistories->firstItem() + $loop->index }}</td>
                                <td>{{ optional($history->tanggal)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $history->produk->nama ?? '-' }}</td>
                                <td>Penjualan</td>
                                <td>
                                    <div class="mono">{{ number_format($history->jumlah, 0, ',', '.') }}</div>
                                    <div class="table-sub">Stok berkurang</div>
                                </td>
                                <td>{{ $history->stok_sebelum }}</td>
                                <td>{{ $history->stok_sesudah }}</td>
                                <td>{{ $history->user->name ?? '-' }}</td>
                                <td>{{ $history->keterangan ?: '-' }}</td>
                                <td>
                                    <span class="table-sub">Tidak bisa diedit</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($salesOutgoingHistories->hasPages())
                    <div class="pagination-bar">
                        <div class="table-sub">
                            Menampilkan {{ $salesOutgoingHistories->firstItem() }}-{{ $salesOutgoingHistories->lastItem() }} dari {{ $salesOutgoingHistories->total() }} riwayat
                        </div>
                        <div class="pagination-pages">
                            @if ($salesOutgoingHistories->onFirstPage())
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $salesOutgoingHistories->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                            @endif

                            @foreach ($salesOutgoingHistories->getUrlRange(1, $salesOutgoingHistories->lastPage()) as $page => $url)
                                @if ($page === $salesOutgoingHistories->currentPage())
                                    <span class="btn btn-primary btn-sm" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="btn btn-secondary btn-sm">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($salesOutgoingHistories->hasMorePages())
                                <a href="{{ $salesOutgoingHistories->nextPageUrl() }}" class="btn btn-secondary btn-sm">Berikutnya</a>
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
        </div>
        <div class="card-body">
            @if ($supplierOutgoingHistories->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada riwayat stok keluar dari supplier pada filter ini.</p>
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
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($supplierOutgoingHistories as $history)
                            <tr>
                                <td>{{ $supplierOutgoingHistories->firstItem() + $loop->index }}</td>
                                <td>{{ optional($history->tanggal)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $history->produk->nama ?? '-' }}</td>
                                <td>{{ $history->supplier->nama ?? 'Manual' }}</td>
                                <td>
                                    <div class="mono">{{ number_format($history->jumlah, 0, ',', '.') }}</div>
                                    <div class="table-sub">Stok berkurang</div>
                                </td>
                                <td>{{ $history->stok_sebelum }}</td>
                                <td>{{ $history->stok_sesudah }}</td>
                                <td>{{ $history->user->name ?? '-' }}</td>
                                <td>{{ $history->keterangan ?: '-' }}</td>
                                <td>
                                    <div class="td-actions">
                                        <a href="{{ route('stok_histories.correction', $history->id) }}" class="btn btn-secondary btn-sm">Buat Koreksi</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($supplierOutgoingHistories->hasPages())
                    <div class="pagination-bar">
                        <div class="table-sub">
                            Menampilkan {{ $supplierOutgoingHistories->firstItem() }}-{{ $supplierOutgoingHistories->lastItem() }} dari {{ $supplierOutgoingHistories->total() }} riwayat
                        </div>
                        <div class="pagination-pages">
                            @if ($supplierOutgoingHistories->onFirstPage())
                                <span class="btn btn-secondary btn-sm" aria-disabled="true">Sebelumnya</span>
                            @else
                                <a href="{{ $supplierOutgoingHistories->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                            @endif

                            @foreach ($supplierOutgoingHistories->getUrlRange(1, $supplierOutgoingHistories->lastPage()) as $page => $url)
                                @if ($page === $supplierOutgoingHistories->currentPage())
                                    <span class="btn btn-primary btn-sm" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="btn btn-secondary btn-sm">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($supplierOutgoingHistories->hasMorePages())
                                <a href="{{ $supplierOutgoingHistories->nextPageUrl() }}" class="btn btn-secondary btn-sm">Berikutnya</a>
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
