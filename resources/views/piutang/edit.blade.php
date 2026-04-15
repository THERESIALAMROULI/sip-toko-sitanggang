@extends('layouts.admin')
@section('title', 'Ubah Status Piutang')
@section('subtitle', 'Ubah status piutang')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Form Ubah Status</div>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            Transaksi: <strong>#{{ $receivable->transaction_id }}</strong> |
            Pelanggan: <strong>{{ $receivable->transaction->customer->name ?? '-' }}</strong> |
            Nominal: <strong>Rp {{ number_format($receivable->amount, 0, ',', '.') }}</strong> |
            Jatuh Tempo: <strong>{{ optional($receivable->due_date)->format('d-m-Y') ?? '-' }}</strong>
        </div>
        <form action="{{ route('receivables.update', $receivable->id) }}" method="POST" class="stack-md">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="unpaid" @selected(old('status', $receivable->status) === 'unpaid')>Belum Lunas</option>
                        <option value="paid" @selected(old('status', $receivable->status) === 'paid')>Lunas</option>
                    </select>
                </div>
                <div class="field">
                    <label>Tanggal Pelunasan Saat Ini</label>
                    <input type="text" value="{{ optional($receivable->paid_at)->format('d-m-Y H:i') ?? '-' }}" readonly>
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('receivables.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
