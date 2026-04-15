@extends('layouts.admin')
@section('title', 'Data Pemasok')
@section('subtitle', 'Data pemasok')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Daftar Pemasok</div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">+ Tambah Pemasok</a>
    </div>
    <div class="card-body">
        @if ($suppliers->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Belum ada data pemasok.</p>
            </div>
        @else
            <div class="tbl-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
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
                            <td>{{ $supplier->alamat ?: '-' }}</td>
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
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pemasok ini?')">Hapus</button>
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
