@extends('layouts.admin')
@section('title', 'Tambah Transaksi')
@section('subtitle', 'Tambah transaksi')
@section('content')
<form action="{{ route('transactions.store') }}" method="POST" class="stack-lg">
    @csrf
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Data Transaksi</div>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="field">
                    <label for="customer_id">Pelanggan</label>
                    <select id="customer_id" name="customer_id">
                        <option value="">Umum / Tanpa pelanggan</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint">Wajib untuk pembayaran utang.</div>
                    @error('customer_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="payment_type">Metode Pembayaran</label>
                    <select id="payment_type" name="payment_type" required>
                        <option value="">Pilih metode pembayaran</option>
                        <option value="tunai" @selected(old('payment_type') === 'tunai')>Tunai</option>
                        <option value="utang" @selected(old('payment_type') === 'utang')>Utang (Piutang)</option>
                    </select>
                    @error('payment_type')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field js-cash-field" id="cash-field">
                    <label for="cash_received">Uang Diterima</label>
                    <input id="cash_received" type="number" name="cash_received" value="{{ old('cash_received') }}" min="0" step="1">
                    @error('cash_received')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field js-cash-field" id="change-field">
                    <label for="change_preview">Kembalian</label>
                    <input id="change_preview" type="text" class="mono" value="Rp 0" readonly>
                </div>
                <div class="field js-due-date-field" id="due-date-field">
                    <label for="due_date">Jatuh Tempo Utang</label>
                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}">
                    @error('due_date')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
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
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td class="mono">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($product->stock <= 0)
                                        <span class="badge badge-red">Habis</span>
                                    @elseif ($product->stock <= 5)
                                        <span class="badge badge-amber">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge badge-green">{{ $product->stock }}</span>
                                    @endif
                                </td>
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
                                        max="{{ $product->stock }}"
                                        value="{{ old('products.'.$index.'.quantity', 0) }}"
                                        class="form-control js-qty-input"
                                        data-price="{{ $product->price }}"
                                        @readonly($product->stock <= 0)
                                    >
                                </td>
                                <td class="mono js-subtotal">Rp 0</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <span class="badge badge-blue">
                        Total sementara:
                        <strong id="estimated-total" class="mono">Rp 0</strong>
                    </span>
                </div>
            @endif
        </div>
    </div>
    <div class="td-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</form>
@endsection
@push('scripts')
<script>
    (() => {
        const quantityInputs = document.querySelectorAll('.js-qty-input');
        const subtotalCells = document.querySelectorAll('.js-subtotal');
        const totalElement = document.getElementById('estimated-total');
        const paymentTypeSelect = document.getElementById('payment_type');
        const cashField = document.getElementById('cash-field');
        const changeField = document.getElementById('change-field');
        const dueDateField = document.getElementById('due-date-field');
        const customerInput = document.getElementById('customer_id');
        const cashInput = document.getElementById('cash_received');
        const dueDateInput = document.getElementById('due_date');
        const changePreview = document.getElementById('change_preview');
        if (!quantityInputs.length || !totalElement) {
            return;
        }
        const formatRupiah = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(value)}`;
        const togglePaymentFields = () => {
            const mode = paymentTypeSelect ? paymentTypeSelect.value : '';
            const isTunai = mode === 'tunai';
            const isUtang = mode === 'utang';
            if (cashField) {
                cashField.style.display = isTunai ? '' : 'none';
            }
            if (changeField) {
                changeField.style.display = isTunai ? '' : 'none';
            }
            if (dueDateField) {
                dueDateField.style.display = isUtang ? '' : 'none';
            }
            if (customerInput) {
                customerInput.required = isUtang;
            }
            if (cashInput) {
                cashInput.required = isTunai;
                if (!isTunai) {
                    cashInput.value = '';
                }
            }
            if (dueDateInput) {
                dueDateInput.required = isUtang;
                if (!isUtang) {
                    dueDateInput.value = '';
                }
            }
        };
        const updateTotals = () => {
            let grandTotal = 0;
            quantityInputs.forEach((input, index) => {
                const price = Number(input.dataset.price || 0);
                const stock = Number(input.max || 0);
                let quantity = Number(input.value || 0);
                if (quantity < 0) {
                    quantity = 0;
                }
                if (quantity > stock) {
                    quantity = stock;
                    input.value = stock;
                }
                const subtotal = quantity * price;
                grandTotal += subtotal;
                if (subtotalCells[index]) {
                    subtotalCells[index].textContent = formatRupiah(subtotal);
                }
            });
            totalElement.textContent = formatRupiah(grandTotal);
            if (changePreview) {
                const cash = Number(cashInput?.value || 0);
                const change = cash - grandTotal;
                changePreview.value = formatRupiah(change > 0 ? change : 0);
            }
        };
        quantityInputs.forEach((input) => {
            input.addEventListener('input', updateTotals);
            input.addEventListener('change', updateTotals);
        });
        if (paymentTypeSelect) {
            paymentTypeSelect.addEventListener('change', () => {
                togglePaymentFields();
                updateTotals();
            });
        }
        if (cashInput) {
            cashInput.addEventListener('input', updateTotals);
            cashInput.addEventListener('change', updateTotals);
        }
        togglePaymentFields();
        updateTotals();
    })();
</script>
@endpush
