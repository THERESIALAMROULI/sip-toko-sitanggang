@extends('layouts.admin')
@section('title', 'Edit Mutasi Stok')
@section('subtitle', 'Perbarui data mutasi stok produk')
@section('content')
@php
    $defaultJenis = old('jenis', $stokHistory->jumlah < 0 ? 'koreksi_kurang' : 'masuk');
@endphp
<div class="card">
    <div class="card-hd">
        <div class="card-title">Form Edit Mutasi Stok</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('stok_histories.update', $stokHistory->id) }}" class="stack-md">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field">
                    <label for="jenis">Jenis Mutasi</label>
                    <select id="jenis" name="jenis" required>
                        <option value="masuk" @selected($defaultJenis === 'masuk')>Barang Masuk</option>
                        <option value="koreksi_tambah" @selected($defaultJenis === 'koreksi_tambah')>Koreksi Tambah</option>
                        <option value="koreksi_kurang" @selected($defaultJenis === 'koreksi_kurang')>Koreksi Kurang</option>
                    </select>
                    @error('jenis')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="produk_id">Produk</label>
                    <select id="produk_id" name="produk_id" required>
                        <option value="">Pilih produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected((int) old('produk_id', $stokHistory->produk_id) === $product->id)>
                                {{ $product->nama }} (stok: {{ $product->stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('produk_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="supplier_id">Supplier</label>
                    <select id="supplier_id" name="supplier_id">
                        <option value="">Tanpa supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected((int) old('supplier_id', $stokHistory->supplier_id ?? 0) === $supplier->id)>{{ $supplier->nama }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="jumlah">Jumlah</label>
                    <input id="jumlah" type="number" name="jumlah" value="{{ old('jumlah', abs((int) $stokHistory->jumlah)) }}" min="1" required>
                    @error('jumlah')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan">{{ old('keterangan', $stokHistory->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Update Mutasi Stok</button>
                <a href="{{ route('stok_histories.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
