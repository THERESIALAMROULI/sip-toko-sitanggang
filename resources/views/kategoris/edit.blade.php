@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('subtitle', 'Perbarui nama kategori')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Form Edit Kategori</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('kategoris.update', $kategori->id) }}" class="stack-md">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field field-full">
                    <label for="nama">Nama Kategori</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama', $kategori->nama) }}" required>
                    @error('nama')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Update Kategori</button>
                <a href="{{ route('kategoris.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
