@extends('layouts.admin')
@section('title', 'Tambah Pemasok')
@section('subtitle', 'Tambah pemasok')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Data Pemasok</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.store') }}" class="stack-md">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label for="nama">Nama Pemasok</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="telp">Telepon</label>
                    <input id="telp" type="text" name="telp" value="{{ old('telp') }}">
                    @error('telp')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="aktif">Status Pemasok</label>
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
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
