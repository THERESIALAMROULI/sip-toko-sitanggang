@extends('layouts.admin')

@section('title', 'Tambah Transaksi')
@section('subtitle', 'Input transaksi penjualan baru')

@section('content')
<form action="{{ route('transactions.store') }}" method="POST" class="stack-lg">
    @csrf

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Informasi Transaksi</div>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="field">
                    <label for="customer_id">Customer</label>
                    <select id="customer_id" name="customer_id">
                        <option value="">Umum / Tanpa Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint">Wajib dipilih jika metode pembayaran kredit.</div>
                </div>

                <div class="field">
                    <label for="payment_type">Metode Pembayaran</label>
                    <select id="payment_type" name="payment_type" required>
                        <option value="">Pilih metode pembayaran</option>
                        <option value="cash" @selected(old('payment_type') === 'cash')>Cash</option>
                        <option value="transfer" @selected(old('payment_type') === 'transfer')>Transfer</option>
                        <option value="qris" @selected(old('payment_type') === 'qris')>QRIS</option>
                        <option value="credit" @selected(old('payment_type') === 'credit')>Kredit (Piutang)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Item Produk</div>
        </div>
        <div class="card-body">
            @if ($products->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Produk belum tersedia.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Qty</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td class="mono">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    <input
                                        type="hidden"
                                        name="products[{{ $index }}][product_id]"
                                        value="{{ $product->id }}"
                                    >
                                    <input
                                        type="number"
                                        name="products[{{ $index }}][quantity]"
                                        min="0"
                                        value="{{ old('products.'.$index.'.quantity', 0) }}"
                                        class="form-control"
                                    >
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="td-actions">
        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</form>
@endsection
