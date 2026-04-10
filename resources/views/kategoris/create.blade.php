@extends('layouts.admin')
@section('title', 'Tambah Kategori')
@section('subtitle', 'Masukkan kategori produk baru')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Form Kategori</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('kategoris.store') }}" class="stack-md">
            @csrf
            <div class="form-grid">
                <div class="field field-full">
                    <label for="nama">Nama Kategori</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                <a href="{{ route('kategoris.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
