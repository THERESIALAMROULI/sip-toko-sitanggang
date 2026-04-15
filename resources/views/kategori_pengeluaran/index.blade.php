@extends('layouts.admin')
@section('title', 'Kategori Biaya')
@section('subtitle', 'Data kategori pengeluaran')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Daftar Kategori Pengeluaran</div>
        <a href="{{ route('expense_categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
    </div>
    <div class="card-body">
        @if ($expenseCategories->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Belum ada kategori biaya.</p>
            </div>
        @else
            <div class="tbl-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Dipakai</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($expenseCategories as $expenseCategory)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $expenseCategory->nama }}</td>
                            <td>{{ $expenseCategory->deskripsi ?: '-' }}</td>
                            <td>
                                @if ($expenseCategory->aktif)
                                    <span class="badge badge-green">Aktif</span>
                                @else
                                    <span class="badge badge-gray">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ number_format($expenseCategory->expenses_count, 0, ',', '.') }} biaya</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('expense_categories.edit', $expenseCategory->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('expense_categories.destroy', $expenseCategory->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori biaya ini?')">Hapus</button>
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
