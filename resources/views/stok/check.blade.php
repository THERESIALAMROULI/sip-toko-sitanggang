@extends('layouts.admin')
@section('title', 'Cek Stok Produk')
@section('subtitle', 'Cek stok')
@section('content')
<div class="card stock-check-card">
    <div class="card-hd">
        <div>
            <div class="card-title">Daftar Stok Produk</div>
            <div class="table-sub">Pantau stok barang berdasarkan status persediaan.</div>
        </div>
    </div>
    <div class="card-body stack-md">
        <form method="GET" action="{{ route('stocks.check') }}" class="search-row">
            <input type="hidden" name="status" value="{{ $selectedStatus !== 'all' ? $selectedStatus : '' }}">
            <input type="text" name="q" class="search-input" placeholder="Cari nama produk..." value="{{ $filters['q'] ?? '' }}">
            <select name="kategori_id" class="filter-sel">
                <option value="">Semua Kategori</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" @selected((int) ($filters['kategori_id'] ?? 0) === $kategori->id)>{{ $kategori->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('stocks.check', ['status' => $selectedStatus !== 'all' ? $selectedStatus : null]) }}" class="btn btn-secondary">Reset</a>
        </form>

        <div class="product-group-tabs stock-status-tabs">
            @foreach ($stockGroups as $group)
                <a
                    href="{{ route('stocks.check', [
                        'status' => $group['key'] !== 'all' ? $group['key'] : null,
                        'q' => ! empty($filters['q']) ? $filters['q'] : null,
                        'kategori_id' => ! empty($filters['kategori_id']) ? $filters['kategori_id'] : null,
                    ]) }}"
                    class="btn {{ $selectedStatus === $group['key'] ? 'btn-primary' : 'btn-secondary' }}"
                >
                    {{ $group['label'] }} ({{ number_format($group['count'], 0, ',', '.') }})
                </a>
            @endforeach
        </div>

        @if ($products->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Tidak ada data stok pada filter ini.</p>
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
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Status Stok</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $products->firstItem() + $loop->index }}</td>
                            <td>{{ $product->nama }}</td>
                            <td>{{ $product->kategori->nama ?? '-' }}</td>
                            <td class="mono">Rp {{ number_format((int) $product->harga_beli, 0, ',', '.') }}</td>
                            <td class="mono">Rp {{ number_format((int) $product->harga_jual, 0, ',', '.') }}</td>
                            <td class="mono">{{ number_format((int) $product->stok, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $product->stock_badge }}">{{ $product->stock_status_label }}</span>
                                <div class="table-sub">Minimum {{ number_format((int) $product->stok_minimum, 0, ',', '.') }}</div>
                            </td>
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
@endsection
