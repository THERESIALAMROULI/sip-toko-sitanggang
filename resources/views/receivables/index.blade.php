@extends('layouts.admin')

@section('title', 'Piutang')
@section('subtitle', 'Daftar piutang dari transaksi kredit')

@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Daftar Piutang</div>
    </div>
    <div class="card-body">
        <p class="form-hint mb-3">
            Piutang terbentuk otomatis dari transaksi dengan metode pembayaran kredit.
        </p>

        @if ($receivables->isEmpty())
            <div class="empty-state">
                <div class="es-icon">-</div>
                <p>Tidak ada piutang.</p>
            </div>
        @else
            <div class="tbl-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Transaksi</th>
                        <th>Customer</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Jatuh Tempo</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($receivables as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>#{{ $item->transaction->id ?? '-' }}</td>
                            <td>{{ $item->transaction->customer->name ?? '-' }}</td>
                            <td class="mono">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td>
                                @if ($item->status === 'paid')
                                    <span class="badge badge-green">Lunas</span>
                                @else
                                    <span class="badge badge-amber">Belum Lunas</span>
                                @endif
                            </td>
                            <td>{{ optional($item->due_date)->format('d-m-Y') ?? '-' }}</td>
                            <td>
                                <a href="{{ route('receivables.edit', $item->id) }}" class="btn btn-secondary btn-sm">Ubah Status</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
