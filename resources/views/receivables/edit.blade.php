@extends('layouts.admin')

@section('title', 'Ubah Status Piutang')
@section('subtitle', 'Perbarui status pembayaran piutang')

@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Form Ubah Status</div>
    </div>
    <div class="card-body">
        <form action="{{ route('receivables.update', $receivable->id) }}" method="POST" class="stack-md">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="unpaid" {{ $receivable->status == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="paid" {{ $receivable->status == 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('receivables.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
