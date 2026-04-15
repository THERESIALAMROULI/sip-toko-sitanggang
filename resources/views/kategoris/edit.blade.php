@extends('layouts.admin')
@section('title', 'Edit Kategori Produk')
@section('subtitle', 'Edit kategori produk')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Edit Kategori Produk</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('kategoris.update', $kategori->id) }}" class="stack-md">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field field-full">
                    <label for="nama">Nama Kategori Produk</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama', $kategori->nama) }}" required>
                    @error('nama')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Update Kategori Produk</button>
                <a href="{{ route('kategoris.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
