@extends('layouts.admin')

@section('title', 'Data Supplier')
@section('subtitle', 'Kelola supplier untuk modul stok')

@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Daftar Supplier</div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">+ Tambah Supplier</a>
    </div>
    <div class="card-body">
        @if ($suppliers->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Belum ada data supplier.</p>
            </div>
        @else
            <div class="tbl-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Total Histori</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $supplier->nama }}</td>
                            <td>{{ $supplier->telp ?: '-' }}</td>
                            <td>
                                @if ($supplier->aktif)
                                    <span class="badge badge-green">Aktif</span>
                                @else
                                    <span class="badge badge-gray">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $supplier->stok_histories_count }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus supplier ini?')">Hapus</button>
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
