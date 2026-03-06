@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('subtitle', 'Rincian item pada transaksi')

@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">Detail Item Transaksi</div>
    </div>
    <div class="card-body">
        <div class="tbl-wrap">
            <table>
                <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($transaction->details as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td class="mono">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td class="mono">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
