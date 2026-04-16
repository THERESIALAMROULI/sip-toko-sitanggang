@extends('layouts.admin')
@section('title', 'Data Produk')
@section('subtitle', 'Data produk')
@section('content')
<div class="card">
    <div class="card-hd">
        <div>
            <div class="card-title">Daftar Produk</div>
            <div class="table-sub">Pilih kategori stok, lalu pindah halaman. Setiap halaman menampilkan 5 produk.</div>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
    </div>
    <div class="card-body stack-md">
        <form method="GET" action="{{ route('products.index') }}" class="search-row">
            <input type="hidden" name="status" value="{{ $selectedStatus }}">
            <input
                id="q"
                type="text"
                name="q"
                class="search-input"
                placeholder="Cari nama produk..."
                value="{{ $search }}"
            >
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('products.index', ['status' => $selectedStatus !== 'all' ? $selectedStatus : null]) }}" class="btn btn-secondary">Reset</a>
        </form>

        <div class="product-group-tabs">
            @foreach ($productGroups as $group)
                <a
                    href="{{ route('products.index', ['status' => $group['key'], 'q' => $search !== '' ? $search : null]) }}"
                    class="btn {{ $selectedStatus === $group['key'] ? 'btn-primary' : 'btn-secondary' }}"
                >
                    {{ $group['label'] }} ({{ number_format($group['count'], 0, ',', '.') }})
                </a>
            @endforeach
        </div>

        @if ($products->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Tidak ada produk pada filter ini.</p>
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
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $products->firstItem() + $loop->index }}</td>
                            <td>{{ $product->nama }}</td>
                            <td>{{ $product->kategori->nama ?? '-' }}</td>
                            <td class="mono">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                            <td class="mono">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                            <td class="mono">{{ number_format($product->stok, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $product->stock_badge }}">{{ $product->stock_status_label }}</span>
                                <div class="table-sub">Minimum {{ number_format($product->stok_minimum, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $product->aktif ? 'badge-green' : 'badge-gray' }}">{{ $product->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                                    </form>
                                </div>
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
