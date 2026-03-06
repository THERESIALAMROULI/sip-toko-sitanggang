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
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->name }}</td>
                            <td class="mono">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                @if ($product->stock <= 0)
                                    <span class="badge badge-red">Habis (0)</span>
                                @elseif ($product->stock <= 5)
                                    <span class="badge badge-amber">Rendah ({{ $product->stock }})</span>
                                @else
                                    <span class="badge badge-green">{{ $product->stock }} tersedia</span>
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
