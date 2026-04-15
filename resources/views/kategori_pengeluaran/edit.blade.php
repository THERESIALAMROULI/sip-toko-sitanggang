@extends('layouts.admin')
@section('title', 'Edit Kategori Biaya')
@section('subtitle', 'Edit kategori pengeluaran')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Edit Kategori Pengeluaran</div>
    </div>
    <div class="card-body">
        <form action="{{ route('expense_categories.update', $expenseCategory->id) }}" method="POST" class="stack-md">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field">
                    <label for="nama">Nama Kategori</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama', $expenseCategory->nama) }}" required>
                    @error('nama')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi">{{ old('deskripsi', $expenseCategory->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="aktif">Status Kategori</label>
                    <select id="aktif" name="aktif" required>
                        <option value="1" @selected(old('aktif', $expenseCategory->aktif ? '1' : '0') === '1')>Aktif</option>
                        <option value="0" @selected(old('aktif', $expenseCategory->aktif ? '1' : '0') === '0')>Nonaktif</option>
                    </select>
                    @error('aktif')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Update Kategori</button>
                <a href="{{ route('expense_categories.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
