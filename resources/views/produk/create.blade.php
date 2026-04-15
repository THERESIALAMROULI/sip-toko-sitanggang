@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('subtitle', 'Tambah produk')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Data Produk</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}" class="stack-md">
            @csrf
            <div class="form-grid">
                <div class="field field-full">
                    <label for="nama">Nama Produk</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Beras 5kg" required>
                    @error('nama')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" @selected((int) old('kategori_id') === $kategori->id)>{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="harga_beli">Harga Beli</label>
                    <input id="harga_beli" type="number" name="harga_beli" value="{{ old('harga_beli', 0) }}" min="0" required>
                    @error('harga_beli')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="harga_jual">Harga Jual</label>
                    <input id="harga_jual" type="number" name="harga_jual" value="{{ old('harga_jual', 0) }}" min="0" required>
                    @error('harga_jual')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="stok">Stok</label>
                    <input id="stok" type="number" name="stok" value="{{ old('stok', 0) }}" min="0" required>
                    <div class="form-hint">Stok awal harus di atas stok minimum.</div>
                    @error('stok')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="stok_minimum">Stok Minimum</label>
                    <input id="stok_minimum" type="number" name="stok_minimum" value="{{ old('stok_minimum', 10) }}" min="0" required>
                    @error('stok_minimum')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="aktif">Status Produk</label>
                    <select id="aktif" name="aktif" required>
                        <option value="1" @selected(old('aktif', '1') === '1')>Aktif</option>
                        <option value="0" @selected(old('aktif') === '0')>Nonaktif</option>
                    </select>
                    @error('aktif')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
