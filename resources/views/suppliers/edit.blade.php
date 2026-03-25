@extends('layouts.admin')

@section('title', 'Edit Supplier')
@section('subtitle', 'Perbarui data supplier')

@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Form Edit Supplier</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}" class="stack-md">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="field">
                    <label for="nama">Nama Supplier</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama', $supplier->nama) }}" required>
                    @error('nama')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="telp">Telepon</label>
                    <input id="telp" type="text" name="telp" value="{{ old('telp', $supplier->telp) }}">
                    @error('telp')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field field-full">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat">{{ old('alamat', $supplier->alamat) }}</textarea>
                    @error('alamat')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field field-full">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan">{{ old('keterangan', $supplier->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field field-full">
                    <label for="aktif">Status Supplier</label>
                    <select id="aktif" name="aktif" required>
                        <option value="1" @selected((string) old('aktif', $supplier->aktif ? '1' : '0') === '1')>Aktif</option>
                        <option value="0" @selected((string) old('aktif', $supplier->aktif ? '1' : '0') === '0')>Nonaktif</option>
                    </select>
                    @error('aktif')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Update Supplier</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
