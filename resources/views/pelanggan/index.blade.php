@extends('layouts.admin')
@section('title', 'Data Pelanggan')
@section('subtitle', 'Data pelanggan')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Daftar Pelanggan</div>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Tambah Pelanggan</a>
    </div>
    <div class="card-body">
        @if ($customers->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Belum ada data pelanggan.</p>
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
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->address ?: '-' }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data pelanggan?')">Hapus</button>
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
