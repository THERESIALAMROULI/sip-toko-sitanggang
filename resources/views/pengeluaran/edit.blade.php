@extends('layouts.admin')
@section('title', 'Edit Biaya Operasional')
@section('subtitle', 'Edit pengeluaran')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Edit Pengeluaran</div>
    </div>
    <div class="card-body">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="stack-md">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field">
                    <label for="expense_category_id">Kategori Biaya</label>
                    <select id="expense_category_id" name="expense_category_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($expenseCategories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('expense_category_id', $expense->expense_category_id) === (string) $category->id)>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('expense_category_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="nominal">Nominal (Rp)</label>
                    <input id="nominal" type="number" min="1" name="nominal" value="{{ old('nominal', $expense->nominal) }}" required>
                    @error('nominal')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="tanggal">Tanggal</label>
                    <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', optional($expense->tanggal)->toDateString()) }}" required>
                    @error('tanggal')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="catatan">Catatan</label>
                    <textarea id="catatan" name="catatan">{{ old('catatan', $expense->catatan) }}</textarea>
                    @error('catatan')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Update Biaya</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
