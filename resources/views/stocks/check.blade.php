@extends('layouts.admin')

@section('title', 'Cek Stok Produk')
@section('subtitle', 'Pantau status ketersediaan produk')

@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Stok</div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('stocks.check') }}" class="search-row">
                <input type="text" name="q" class="search-input" placeholder="Cari nama produk..." value="{{ $filters['q'] ?? '' }}">

                <select name="kategori_id" class="filter-sel">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected((int) ($filters['kategori_id'] ?? 0) === $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>

                <select name="status" class="filter-sel">
                    <option value="">Semua Status</option>
                    <option value="normal" @selected(($filters['status'] ?? null) === 'normal')>Normal</option>
                    <option value="low" @selected(($filters['status'] ?? null) === 'low')>Hampir Habis</option>
                    <option value="out" @selected(($filters['status'] ?? null) === 'out')>Habis</option>
                    <option value="inactive" @selected(($filters['status'] ?? null) === 'inactive')>Nonaktif</option>
                </select>

                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="{{ route('stocks.check') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Daftar Stok Produk</div>
        </div>
        <div class="card-body">
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
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Stok Minimum</th>
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
                                <td>{{ $loop->iteration }}</td>
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
</div>
@endsection
