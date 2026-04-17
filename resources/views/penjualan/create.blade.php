@extends('layouts.admin')
@section('title', 'Transaksi Penjualan Baru')
@section('subtitle', 'Pilih produk dan proses pembayaran')
@section('content')
@php
    $selectedPaymentType = old('payment_type', 'tunai');
    $categoryNames = $products
        ->map(fn ($product) => $product->kategori->nama ?? 'Tanpa Kategori')
        ->filter()
        ->unique()
        ->sort()
        ->values();
@endphp
<form action="{{ route('transactions.store') }}" method="POST" class="pos-form">
    @csrf
    <div class="pos-toolbar">
        <div>
            <div class="pos-kicker">Transaksi kasir</div>
            <div class="pos-note">Nota baru dibuat otomatis setelah transaksi tersimpan.</div>
        </div>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="pos-layout">
        <section class="pos-product-panel" aria-label="Daftar produk">
            <div class="pos-search-wrap">
                <input id="pos-product-search" type="search" class="pos-search-input" placeholder="Cari produk..." autocomplete="off">
            </div>
            <div class="pos-category-tabs" aria-label="Filter kategori produk">
                <button type="button" class="pos-category-tab is-active" data-category-filter="all">Semua</button>
                @foreach ($categoryNames as $categoryName)
                    <button type="button" class="pos-category-tab" data-category-filter="{{ strtolower($categoryName) }}">{{ $categoryName }}</button>
                @endforeach
            </div>

            @if ($products->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Produk belum tersedia.</p>
                </div>
            @else
                <div class="pos-product-grid" id="pos-product-grid">
                    @foreach ($products as $index => $product)
                        @php
                            $categoryName = $product->kategori->nama ?? 'Tanpa Kategori';
                            $oldQuantity = (int) old('products.'.$index.'.quantity', 0);
                            $stock = (int) $product->stock;
                        @endphp
                        <article
                            class="pos-product-card"
                            data-product-card
                            data-search="{{ strtolower($product->name.' '.$categoryName) }}"
                            data-category="{{ strtolower($categoryName) }}"
                        >
                            <input type="hidden" name="products[{{ $index }}][product_id]" value="{{ $product->id }}">
                            <input
                                type="hidden"
                                name="products[{{ $index }}][quantity]"
                                value="{{ $oldQuantity > 0 ? $oldQuantity : '' }}"
                                min="0"
                                max="{{ $stock }}"
                                class="js-qty-input"
                                data-product-index="{{ $index }}"
                                data-name="{{ $product->name }}"
                                data-category="{{ $categoryName }}"
                                data-price="{{ $product->price }}"
                                data-stock="{{ $stock }}"
                            >
                            <button type="button" class="pos-product-button js-product-add" @disabled($stock <= 0)>
                                <span class="pos-selected-count" data-selected-count hidden>0</span>
                                <span class="pos-product-main">
                                    <span class="pos-product-name">{{ $product->name }}</span>
                                    <span class="pos-product-category">{{ $categoryName }}</span>
                                </span>
                                <span class="pos-product-price mono">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="pos-product-stock {{ $stock <= 5 ? 'is-low' : '' }}">
                                    @if ($stock <= 0)
                                        Stok habis
                                    @else
                                        Stok: {{ number_format($stock, 0, ',', '.') }}
                                    @endif
                                </span>
                            </button>
                        </article>
                    @endforeach
                </div>
                <div class="pos-product-empty" id="pos-product-empty" hidden>Tidak ada produk yang cocok.</div>
                @error('products')
                    <div class="field-error mt-3">{{ $message }}</div>
                @enderror
            @endif
        </section>

        <aside class="pos-cart-panel" aria-label="Keranjang belanja">
            <div class="pos-cart-head">
                <div>
                    <div class="pos-cart-title">Keranjang Belanja</div>
                    <div class="pos-cart-count" id="cart-count">0 item</div>
                </div>
            </div>

            <div class="pos-customer-card">
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
                    <div class="form-hint" id="payment-type-hint">Pembeli umum hanya bisa menggunakan pembayaran tunai.</div>
                    @error('customer_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pos-cart-items" id="cart-items">
                <div class="pos-cart-empty" id="cart-empty">
                    Pilih produk dari daftar di sebelah kiri.
                </div>
            </div>

            <input id="payment_type" type="hidden" name="payment_type" value="{{ $selectedPaymentType }}">
            <div class="pos-payment-grid" aria-label="Metode pembayaran">
                <button type="button" class="pos-payment-choice js-payment-toggle" data-payment="tunai">
                    <span class="pos-payment-icon">Rp</span>
                    Tunai
                </button>
                <button type="button" class="pos-payment-choice js-payment-toggle" data-payment="utang">
                    <span class="pos-payment-icon">UT</span>
                    Utang
                </button>
            </div>
            @error('payment_type')
                <div class="field-error">{{ $message }}</div>
            @enderror

            <div class="pos-summary">
                <div class="pos-summary-row">
                    <span>Subtotal</span>
                    <strong id="estimated-subtotal" class="mono">Rp 0</strong>
                </div>
                <div class="pos-total-row">
                    <span>Total</span>
                    <strong id="estimated-total" class="mono">Rp 0</strong>
                </div>
            </div>

            <div class="pos-payment-fields">
                <div class="field js-cash-field" id="cash-field">
                    <label for="cash_received">Uang Diterima (Rp)</label>
                    <input id="cash_received" type="number" name="cash_received" value="{{ old('cash_received') }}" min="0" step="1" inputmode="numeric">
                    @error('cash_received')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field js-due-date-field" id="due-date-field">
                    <label for="due_date">Jatuh Tempo Utang</label>
                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}">
                    @error('due_date')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pos-change-box js-cash-field" id="change-field">
                <span>Kembalian</span>
                <strong id="change_preview" class="mono">Rp 0</strong>
            </div>

            <button type="submit" class="btn btn-primary pos-submit" id="pos-submit">Proses Transaksi</button>
        </aside>
    </div>
</form>
@endsection
@push('scripts')
<script>
    (() => {
        const quantityInputs = Array.from(document.querySelectorAll('.js-qty-input'));
        const productCards = Array.from(document.querySelectorAll('[data-product-card]'));
        const productGrid = document.getElementById('pos-product-grid');
        const productEmpty = document.getElementById('pos-product-empty');
        const searchInput = document.getElementById('pos-product-search');
        const categoryButtons = Array.from(document.querySelectorAll('[data-category-filter]'));
        const cartItems = document.getElementById('cart-items');
        const cartEmpty = document.getElementById('cart-empty');
        const cartCount = document.getElementById('cart-count');
        const subtotalElement = document.getElementById('estimated-subtotal');
        const totalElement = document.getElementById('estimated-total');
        const paymentTypeInput = document.getElementById('payment_type');
        const paymentButtons = Array.from(document.querySelectorAll('.js-payment-toggle'));
        const cashField = document.getElementById('cash-field');
        const changeField = document.getElementById('change-field');
        const dueDateField = document.getElementById('due-date-field');
        const customerInput = document.getElementById('customer_id');
        const paymentTypeHint = document.getElementById('payment-type-hint');
        const cashInput = document.getElementById('cash_received');
        const dueDateInput = document.getElementById('due_date');
        const changePreview = document.getElementById('change_preview');
        const submitButton = document.getElementById('pos-submit');

        if (!quantityInputs.length || !totalElement || !cartItems) {
            return;
        }

        let activeCategory = 'all';
        const formatRupiah = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(value)}`;
        const clampQuantity = (quantity, stock) => Math.max(0, Math.min(Number(quantity || 0), Number(stock || 0)));
        const quantityValue = (input) => Number(input.value || 0);

        const setInputQuantity = (input, quantity) => {
            const stock = Number(input.dataset.stock || input.max || 0);
            const nextQuantity = clampQuantity(quantity, stock);
            input.value = nextQuantity > 0 ? String(nextQuantity) : '';
            updateTotals();
        };

        const makeElement = (tag, className, text) => {
            const element = document.createElement(tag);
            if (className) {
                element.className = className;
            }
            if (text !== undefined) {
                element.textContent = text;
            }
            return element;
        };

        const renderCart = (items) => {
            cartItems.innerHTML = '';
            if (items.length === 0) {
                cartItems.appendChild(cartEmpty);
                cartEmpty.hidden = false;
                return;
            }

            cartEmpty.hidden = true;
            items.forEach((item) => {
                const row = makeElement('div', 'pos-cart-item');
                const main = makeElement('div', 'pos-cart-item-main');
                main.appendChild(makeElement('div', 'pos-cart-item-name', item.name));
                main.appendChild(makeElement('div', 'pos-cart-item-price mono', `${formatRupiah(item.price)} / pcs`));

                const controls = makeElement('div', 'pos-cart-controls');
                const stepper = makeElement('div', 'pos-stepper');
                const minusButton = makeElement('button', 'pos-stepper-btn', '-');
                minusButton.type = 'button';
                minusButton.dataset.cartAction = 'decrease';
                minusButton.dataset.productIndex = item.index;

                const qtyLabel = makeElement('span', 'pos-cart-qty mono', item.quantity);

                const plusButton = makeElement('button', 'pos-stepper-btn', '+');
                plusButton.type = 'button';
                plusButton.dataset.cartAction = 'increase';
                plusButton.dataset.productIndex = item.index;
                plusButton.disabled = item.quantity >= item.stock;

                stepper.append(minusButton, qtyLabel, plusButton);

                const subtotal = makeElement('strong', 'pos-cart-subtotal mono', formatRupiah(item.subtotal));
                const removeButton = makeElement('button', 'pos-remove-btn', 'x');
                removeButton.type = 'button';
                removeButton.dataset.cartAction = 'remove';
                removeButton.dataset.productIndex = item.index;

                controls.append(stepper, subtotal, removeButton);
                row.append(main, controls);
                cartItems.appendChild(row);
            });
        };

        const updateTotals = () => {
            let grandTotal = 0;
            let totalItems = 0;
            const cartData = [];

            quantityInputs.forEach((input) => {
                const stock = Number(input.dataset.stock || input.max || 0);
                const quantity = clampQuantity(input.value, stock);
                input.value = quantity > 0 ? String(quantity) : '';

                const card = input.closest('[data-product-card]');
                const selectedCount = card?.querySelector('[data-selected-count]');
                if (card) {
                    card.classList.toggle('is-selected', quantity > 0);
                }
                if (selectedCount) {
                    selectedCount.textContent = quantity;
                    selectedCount.hidden = quantity <= 0;
                }

                if (quantity <= 0) {
                    return;
                }

                const price = Number(input.dataset.price || 0);
                const subtotal = quantity * price;
                grandTotal += subtotal;
                totalItems += quantity;
                cartData.push({
                    index: input.dataset.productIndex,
                    name: input.dataset.name || '-',
                    price,
                    quantity,
                    stock,
                    subtotal,
                });
            });

            subtotalElement.textContent = formatRupiah(grandTotal);
            totalElement.textContent = formatRupiah(grandTotal);
            cartCount.textContent = `${totalItems} item`;
            renderCart(cartData);

            if (changePreview) {
                const cash = Number(cashInput?.value || 0);
                const change = cash - grandTotal;
                changePreview.textContent = formatRupiah(change > 0 ? change : 0);
            }
            if (submitButton) {
                submitButton.disabled = grandTotal <= 0;
            }
        };

        const setPaymentMode = (mode) => {
            const hasCustomer = Boolean(customerInput?.value);
            const nextMode = mode === 'utang' && !hasCustomer ? 'tunai' : mode;
            paymentTypeInput.value = nextMode;

            paymentButtons.forEach((button) => {
                const isUtangButton = button.dataset.payment === 'utang';
                button.disabled = isUtangButton && !hasCustomer;
                button.classList.toggle('is-active', button.dataset.payment === nextMode);
            });

            if (paymentTypeHint) {
                paymentTypeHint.textContent = hasCustomer
                    ? 'Pelanggan bisa memakai tunai atau utang.'
                    : 'Pembeli umum hanya bisa menggunakan pembayaran tunai.';
            }

            const isTunai = nextMode === 'tunai';
            const isUtang = nextMode === 'utang';
            cashField.hidden = !isTunai;
            changeField.hidden = !isTunai;
            dueDateField.hidden = !isUtang;

            cashInput.required = isTunai;
            dueDateInput.required = isUtang;
            customerInput.required = isUtang;

            if (!isTunai) {
                cashInput.value = '';
            }
            if (!isUtang) {
                dueDateInput.value = '';
            }
            updateTotals();
        };

        const filterProducts = () => {
            const keyword = (searchInput?.value || '').trim().toLowerCase();
            let visibleCount = 0;

            productCards.forEach((card) => {
                const matchesSearch = !keyword || (card.dataset.search || '').includes(keyword);
                const matchesCategory = activeCategory === 'all' || card.dataset.category === activeCategory;
                const isVisible = matchesSearch && matchesCategory;
                card.hidden = !isVisible;
                if (isVisible) {
                    visibleCount += 1;
                }
            });

            if (productEmpty) {
                productEmpty.hidden = visibleCount > 0;
            }
            if (productGrid) {
                productGrid.hidden = visibleCount === 0;
            }
        };

        document.querySelectorAll('.js-product-add').forEach((button) => {
            button.addEventListener('click', () => {
                const input = button.closest('[data-product-card]')?.querySelector('.js-qty-input');
                if (!input) {
                    return;
                }
                setInputQuantity(input, quantityValue(input) + 1);
            });
        });

        cartItems.addEventListener('click', (event) => {
            const button = event.target.closest('[data-cart-action]');
            if (!button) {
                return;
            }
            const input = quantityInputs.find((item) => item.dataset.productIndex === button.dataset.productIndex);
            if (!input) {
                return;
            }
            if (button.dataset.cartAction === 'increase') {
                setInputQuantity(input, quantityValue(input) + 1);
            }
            if (button.dataset.cartAction === 'decrease') {
                setInputQuantity(input, quantityValue(input) - 1);
            }
            if (button.dataset.cartAction === 'remove') {
                setInputQuantity(input, 0);
            }
        });

        paymentButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setPaymentMode(button.dataset.payment);
            });
        });

        customerInput?.addEventListener('change', () => {
            setPaymentMode(paymentTypeInput.value || 'tunai');
        });

        cashInput?.addEventListener('input', updateTotals);
        cashInput?.addEventListener('change', updateTotals);
        searchInput?.addEventListener('input', filterProducts);

        categoryButtons.forEach((button) => {
            button.addEventListener('click', () => {
                activeCategory = button.dataset.categoryFilter || 'all';
                categoryButtons.forEach((item) => item.classList.remove('is-active'));
                button.classList.add('is-active');
                filterProducts();
            });
        });

        setPaymentMode(paymentTypeInput.value || 'tunai');
        filterProducts();
        updateTotals();
    })();
</script>
@endpush
