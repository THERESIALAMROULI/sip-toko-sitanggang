@extends('layouts.admin')
@section('title', 'Piutang')
@section('subtitle', 'Data piutang')
@section('content')
@php
    $isKasir = (auth()->user()->role ?? null) === 'kasir';
@endphp
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Piutang</div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('receivables.index') }}" class="form-grid">
                <div class="field">
                    <label for="q">Cari</label>
                    <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="ID transaksi / nama pelanggan">
                </div>
                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="unpaid" @selected(($filters['status'] ?? null) === 'unpaid')>Belum Lunas</option>
                        <option value="paid" @selected(($filters['status'] ?? null) === 'paid')>Lunas</option>
                    </select>
                </div>
                <div class="field">
                    <label for="customer_id">Pelanggan</label>
                    <select id="customer_id" name="customer_id">
                        <option value="">Semua Pelanggan</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected((string) ($filters['customer_id'] ?? '') === (string) $customer->id)>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="overdue_only">Lewat Jatuh Tempo</label>
                    <select id="overdue_only" name="overdue_only">
                        <option value="">Semua</option>
                        <option value="1" @selected(($filters['overdue_only'] ?? null) === '1')>Hanya yang lewat tempo</option>
                    </select>
                </div>
                <div class="td-actions field-full">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('receivables.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="stat-grid">
        <div class="stat-card sc-amber">
            <div class="sc-label">Total Belum Lunas</div>
            <div class="sc-value mono">Rp {{ number_format($summaryUnpaidAmount, 0, ',', '.') }}</div>
            <div class="sc-sub">Sesuai filter</div>
        </div>
        <div class="stat-card sc-red">
            <div class="sc-label">Lewat Jatuh Tempo</div>
            <div class="sc-value mono">Rp {{ number_format($summaryOverdueAmount, 0, ',', '.') }}</div>
            <div class="sc-sub">Belum lunas dan lewat tempo</div>
        </div>
        <div class="stat-card sc-blue">
            <div class="sc-label">Piutang Lunas</div>
            <div class="sc-value">{{ number_format($summaryPaidCount, 0, ',', '.') }}</div>
            <div class="sc-sub">Jumlah piutang lunas</div>
        </div>
    </div>
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Daftar Piutang</div>
            <span class="badge badge-blue">{{ $receivables->count() }} data</span>
        </div>
        <div class="card-body">
            <p class="form-hint mb-3">
                Piutang dibuat otomatis dari transaksi utang.
            </p>
            @if ($receivables->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada piutang pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Jatuh Tempo</th>
                            <th>Tanggal Pelunasan</th>
                            @if (! $isKasir)
                                <th>Umur</th>
                            @endif
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($receivables as $item)
                            @php
                                $isOverdue = $item->status === 'unpaid' && $item->due_date && $item->due_date->lt(now());
                                $ageDays = $item->created_at ? $item->created_at->diffInDays(now()) : null;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="mono">#{{ $item->transaction_id }}</td>
                                <td>{{ $item->transaction->customer->name ?? '-' }}</td>
                                <td class="mono">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if ($item->status === 'paid')
                                        <span class="badge badge-green">Lunas</span>
                                    @elseif ($isOverdue)
                                        <span class="badge badge-red">Lewat Tempo</span>
                                    @else
                                        <span class="badge badge-amber">Belum Lunas</span>
                                    @endif
                                </td>
                                <td>{{ optional($item->due_date)->format('d-m-Y') ?? '-' }}</td>
                                <td>{{ optional($item->paid_at)->format('d-m-Y H:i') ?? '-' }}</td>
                                @if (! $isKasir)
                                    <td>{{ $ageDays !== null ? $ageDays.' hari' : '-' }}</td>
                                @endif
                                <td>
                                    <a href="{{ route('receivables.edit', $item->id) }}" class="btn btn-secondary btn-sm">Ubah</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
