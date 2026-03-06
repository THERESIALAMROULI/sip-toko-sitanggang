@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('subtitle', 'Masukkan data produk baru')

@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Form Produk</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}" class="stack-md">
            @csrf

            <div class="form-grid">
                <div class="field field-full">
                    <label for="name">Nama Produk</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Beras 5kg" required>
                    @error('name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="price">Harga</label>
                    <input id="price" type="number" name="price" value="{{ old('price') }}" min="0" required>
                    @error('price')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="stock">Stok</label>
                    <input id="stock" type="number" name="stock" value="{{ old('stock') }}" min="0" required>
                    @error('stock')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
