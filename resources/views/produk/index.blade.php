@extends('layouts.admin')
@section('title', 'Data Produk')
@section('subtitle', 'Kelola daftar produk dan stok')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Daftar Produk</div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
    </div>
    <div class="card-body">
        @if ($products->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Belum ada produk yang tersimpan.</p>
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
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->nama }}</td>
                            <td>{{ $product->kategori->nama ?? '-' }}</td>
                            <td class="mono">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                            <td class="mono">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                            <td>
                                @if ($product->stok <= 0)
                                    <span class="badge badge-red">Habis (0)</span>
                                @elseif ($product->stok <= $product->stok_minimum)
                                    <span class="badge badge-amber">Rendah ({{ $product->stok }})</span>
                                @else
                                    <span class="badge badge-green">{{ $product->stok }} tersedia</span>
                                @endif
                            </td>
                            <td>
                                @if ($product->aktif)
                                    <span class="badge badge-green">Aktif</span>
                                @else
                                    <span class="badge badge-gray">Nonaktif</span>
                                @endif
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
        @endif
    </div>
</div>
@endsection
