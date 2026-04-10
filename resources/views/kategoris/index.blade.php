@extends('layouts.admin')
@section('title', 'Data Kategori')
@section('subtitle', 'Kelola kategori produk sesuai ERD')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Daftar Kategori</div>
        <a href="{{ route('kategoris.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
    </div>
    <div class="card-body">
        @if ($kategoris->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Belum ada data kategori.</p>
            </div>
        @else
            <div class="tbl-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Produk</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($kategoris as $kategori)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kategori->nama }}</td>
                            <td>{{ $kategori->products_count }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('kategoris.edit', $kategori->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('kategoris.destroy', $kategori->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
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
