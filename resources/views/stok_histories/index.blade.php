@extends('layouts.admin')
@section('title', 'Manajemen Stok')
@section('subtitle', 'Riwayat stok')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Riwayat Stok</div>
        @if ((Auth::user()->role ?? null) === 'admin')
            <a href="{{ route('stok_histories.create') }}" class="btn btn-primary">+ Tambah Riwayat Stok</a>
        @endif
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            Riwayat stok adalah catatan tetap. Jika ada salah input, buat catatan koreksi baru agar jejak perubahan stok tetap jelas.
        </div>
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
        @if ($stokHistories->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Belum ada riwayat stok.</p>
            </div>
        @else
            <div class="tbl-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Pemasok</th>
                        <th>Perubahan</th>
                        <th>Stok Sebelum</th>
                        <th>Stok Sesudah</th>
                        <th>Petugas</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($stokHistories as $history)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($history->tanggal)->format('d/m/Y H:i') ?? '-' }}</td>
                            <td>{{ $history->produk->nama ?? '-' }}</td>
                            <td>{{ $history->supplier->nama ?? '-' }}</td>
                            <td>
                                <div class="mono">{{ $history->jumlah > 0 ? '+' : '' }}{{ number_format($history->jumlah, 0, ',', '.') }}</div>
                                <div class="table-sub">{{ $history->jumlah > 0 ? 'Stok bertambah' : 'Stok berkurang' }}</div>
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
        @endif
    </div>
</div>
@endsection
