@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('subtitle', 'Perbarui data produk')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Form Edit Produk</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product->id) }}" class="stack-md">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field field-full">
                    <label for="nama">Nama Produk</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama', $product->nama) }}" required>
                    @error('nama')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id" required>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" @selected((int) old('kategori_id', $product->kategori_id) === $kategori->id)>{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="harga_beli">Harga Beli</label>
                    <input id="harga_beli" type="number" name="harga_beli" value="{{ old('harga_beli', $product->harga_beli) }}" min="0" required>
                    @error('harga_beli')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="harga_jual">Harga Jual</label>
                    <input id="harga_jual" type="number" name="harga_jual" value="{{ old('harga_jual', $product->harga_jual) }}" min="0" required>
                    @error('harga_jual')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="stok">Stok</label>
                    <input id="stok" type="number" name="stok" value="{{ old('stok', $product->stok) }}" min="0" required>
                    @error('stok')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="stok_minimum">Stok Minimum</label>
                    <input id="stok_minimum" type="number" name="stok_minimum" value="{{ old('stok_minimum', $product->stok_minimum) }}" min="0" required>
                    @error('stok_minimum')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="aktif">Status Produk</label>
                    <select id="aktif" name="aktif" required>
                        <option value="1" @selected((string) old('aktif', $product->aktif ? '1' : '0') === '1')>Aktif</option>
                        <option value="0" @selected((string) old('aktif', $product->aktif ? '1' : '0') === '0')>Nonaktif</option>
                    </select>
                    @error('aktif')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Update Produk</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
