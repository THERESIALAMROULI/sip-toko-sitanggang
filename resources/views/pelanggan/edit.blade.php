@extends('layouts.admin')
@section('title', 'Edit Pelanggan')
@section('subtitle', 'Edit pelanggan')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Edit Pelanggan</div>
    </div>
    <div class="card-body">
        <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="stack-md">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field">
                    <label for="name">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $customer->name) }}" required>
                    @error('name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="phone">Telepon</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                    @error('phone')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="address">Alamat</label>
                    <textarea id="address" name="address" required>{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Update Pelanggan</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
