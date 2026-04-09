{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Tambah Transaksi')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Input transaksi penjualan baru')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
<form action="{{ route('transactions.store') }}" method="POST" class="stack-lg">
    {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
    @csrf

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Informasi Transaksi</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="customer_id">Customer</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="customer_id" name="customer_id">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Umum / Tanpa Customer</option>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($customers as $customer)
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                {{-- Menampilkan data dinamis dari server ke halaman. --}}
                                {{ $customer->name }}
                            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                            </option>
                        {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                        @endforeach
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="form-hint">Wajib dipilih jika metode pembayaran utang.</div>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="payment_type">Metode Pembayaran</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="payment_type" name="payment_type" required>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Pilih metode pembayaran</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="tunai" @selected(old('payment_type') === 'tunai')>Tunai</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="utang" @selected(old('payment_type') === 'utang')>Utang (Piutang)</option>
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field js-cash-field" id="cash-field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="cash_received">Uang Diterima</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="cash_received" type="number" name="cash_received" value="{{ old('cash_received') }}" min="0" step="1">
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('cash_received')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field js-cash-field" id="change-field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="change_preview">Kembalian</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="change_preview" type="text" class="mono" value="Rp 0" readonly>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field js-due-date-field" id="due-date-field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="due_date">Jatuh Tempo Utang</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}">
                    {{-- Menampilkan pesan error validasi untuk field terkait. --}}
                    @error('due_date')
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="field-error">{{ $message }}</div>
                    {{-- Menutup blok tampilan error validasi. --}}
                    @enderror
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Item Produk</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($products->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Produk belum tersedia.</p>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
            @else
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="tbl-wrap">
                    {{-- Membuka tabel untuk menampilkan data dalam format baris dan kolom. --}}
                    <table>
                        {{-- Membuka bagian kepala tabel untuk judul kolom. --}}
                        <thead>
                        {{-- Membuka baris baru pada tabel. --}}
                        <tr>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>No</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Produk</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Harga</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Stok</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Qty</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Subtotal</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($products as $index => $product)
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $loop->iteration }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $product->name }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                    @if ($product->stock <= 0)
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-red">Habis</span>
                                    {{-- Memeriksa kondisi alternatif pada tampilan. --}}
                                    @elseif ($product->stock <= 5)
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-amber">{{ $product->stock }}</span>
                                    {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                                    @else
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-green">{{ $product->stock }}</span>
                                    {{-- Menutup percabangan kondisi pada template Blade. --}}
                                    @endif
                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                </td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                                    <input
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        type="hidden"
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        name="products[{{ $index }}][product_id]"
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        value="{{ $product->id }}"
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    >
                                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                                    <input
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        type="number"
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        name="products[{{ $index }}][quantity]"
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        min="0"
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        max="{{ $product->stock }}"
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        value="{{ old('products.'.$index.'.quantity', 0) }}"
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        class="form-control js-qty-input"
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        data-price="{{ $product->price }}"
                                        {{-- Menjalankan directive Blade sebagai bagian dari logika tampilan. --}}
                                        @readonly($product->stock <= 0)
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    >
                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                </td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono js-subtotal">Rp 0</td>
                            {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                            </tr>
                        {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                        @endforeach
                        {{-- Menutup bagian isi tabel. --}}
                        </tbody>
                    {{-- Menutup tabel setelah seluruh data selesai ditampilkan. --}}
                    </table>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="mt-3">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="badge badge-blue">
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        Estimasi total:
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <strong id="estimated-total" class="mono">Rp 0</strong>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup percabangan kondisi pada template Blade. --}}
            @endif
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="td-actions">
        {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
</form>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection

{{-- Menambahkan konten ke stack tertentu pada layout. --}}
@push('scripts')
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script>
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    (() => {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const quantityInputs = document.querySelectorAll('.js-qty-input');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const subtotalCells = document.querySelectorAll('.js-subtotal');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const totalElement = document.getElementById('estimated-total');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const paymentTypeSelect = document.getElementById('payment_type');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const cashField = document.getElementById('cash-field');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const changeField = document.getElementById('change-field');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const dueDateField = document.getElementById('due-date-field');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const cashInput = document.getElementById('cash_received');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const dueDateInput = document.getElementById('due_date');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const changePreview = document.getElementById('change_preview');

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        if (!quantityInputs.length || !totalElement) {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            return;
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const formatRupiah = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(value)}`;

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const togglePaymentFields = () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const mode = paymentTypeSelect ? paymentTypeSelect.value : '';
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const isTunai = mode === 'tunai';
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const isUtang = mode === 'utang';

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (cashField) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                cashField.style.display = isTunai ? '' : 'none';
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (changeField) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                changeField.style.display = isTunai ? '' : 'none';
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (dueDateField) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                dueDateField.style.display = isUtang ? '' : 'none';
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (cashInput) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                cashInput.required = isTunai;
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                if (!isTunai) {
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    cashInput.value = '';
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (dueDateInput) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                dueDateInput.required = isUtang;
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                if (!isUtang) {
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    dueDateInput.value = '';
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        };

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const updateTotals = () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            let grandTotal = 0;

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            quantityInputs.forEach((input, index) => {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                const price = Number(input.dataset.price || 0);
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                const stock = Number(input.max || 0);
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                let quantity = Number(input.value || 0);

                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                if (quantity < 0) {
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    quantity = 0;
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                }

                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                if (quantity > stock) {
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    quantity = stock;
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    input.value = stock;
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                }

                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                const subtotal = quantity * price;
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                grandTotal += subtotal;

                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                if (subtotalCells[index]) {
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    subtotalCells[index].textContent = formatRupiah(subtotal);
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            });

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            totalElement.textContent = formatRupiah(grandTotal);

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (changePreview) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                const cash = Number(cashInput?.value || 0);
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                const change = cash - grandTotal;
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                changePreview.value = formatRupiah(change > 0 ? change : 0);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        };

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        quantityInputs.forEach((input) => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            input.addEventListener('input', updateTotals);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            input.addEventListener('change', updateTotals);
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        if (paymentTypeSelect) {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            paymentTypeSelect.addEventListener('change', () => {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                togglePaymentFields();
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                updateTotals();
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            });
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        if (cashInput) {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            cashInput.addEventListener('input', updateTotals);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            cashInput.addEventListener('change', updateTotals);
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        togglePaymentFields();
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        updateTotals();
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    })();
{{-- Menutup blok JavaScript pada halaman ini. --}}
</script>
{{-- Menutup blok push pada template Blade. --}}
@endpush
