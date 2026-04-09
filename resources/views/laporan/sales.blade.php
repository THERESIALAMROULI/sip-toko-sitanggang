{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Laporan Penjualan')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Analisis data penjualan berdasarkan filter')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stack-lg">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Filter Laporan</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="td-actions">
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="button" class="btn btn-secondary" id="exportSalesExcel">Unduh Excel</button>
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="button" class="btn btn-outline" id="exportSalesPdf">Unduh PDF</button>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="GET" action="{{ route('reports.sales') }}" class="form-grid">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="start_date">Dari Tanggal</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="start_date" type="date" name="start_date" value="{{ request('start_date') }}">
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="end_date">Sampai Tanggal</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="end_date" type="date" name="end_date" value="{{ request('end_date') }}">
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="payment_type">Metode Pembayaran</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="payment_type" name="payment_type">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="tunai" @selected(request('payment_type') === 'tunai')>Tunai</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="utang" @selected(request('payment_type') === 'utang')>Utang</option>
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="customer_id">Pelanggan</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="customer_id" name="customer_id">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua Pelanggan</option>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($customers as $customer)
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="{{ $customer->id }}" @selected((string) request('customer_id') === (string) $customer->id)>
                                {{-- Menampilkan data dinamis dari server ke halaman. --}}
                                {{ $customer->name }}
                            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                            </option>
                        {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                        @endforeach
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="td-actions field-full">
                    {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('reports.sales') }}" class="btn btn-secondary">Reset</a>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
            </form>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="stat-grid">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-green">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Total Penjualan</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Akumulasi nominal transaksi</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-blue">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Jumlah Transaksi</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value">{{ number_format($totalTransactions, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Data transaksi pada periode terfilter</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-amber">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Rata-rata per Transaksi</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($averagePerTransaction, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Rata-rata nilai tiap transaksi</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-purple">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Total Utang</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($creditSales, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Nilai transaksi dengan metode utang</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="grid-2">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-hd">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="card-title">Grafik Penjualan</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <canvas id="salesChart" height="100"></canvas>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-hd">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="card-title">Produk Terlaris</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($topProducts->isEmpty())
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="empty-state">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="es-icon">-</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <p>Belum ada data produk terjual.</p>
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
                                <th>Produk</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Qty Terjual</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Total</th>
                            {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                            </tr>
                            {{-- Menutup bagian kepala tabel. --}}
                            </thead>
                            {{-- Membuka bagian isi tabel untuk data utama. --}}
                            <tbody>
                            {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                            @foreach ($topProducts as $item)
                                {{-- Membuka baris baru pada tabel. --}}
                                <tr>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $item->product_name }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ number_format($item->qty_sold, 0, ',', '.') }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td class="mono">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
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
                {{-- Menutup percabangan kondisi pada template Blade. --}}
                @endif
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
            <div class="card-title">Detail Transaksi</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($transactions->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Tidak ada data transaksi pada filter ini.</p>
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
                            <th>Tanggal</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Customer</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Pembayaran</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Total</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($transactions as $transaction)
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $loop->iteration }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $transaction->transaction_date->format('d-m-Y H:i') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $transaction->customer->name ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                    @if ($transaction->payment_type === 'tunai')
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-green">TUNAI</span>
                                    {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                                    @else
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-amber">UTANG</span>
                                    {{-- Menutup percabangan kondisi pada template Blade. --}}
                                    @endif
                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                </td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
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
            {{-- Menutup percabangan kondisi pada template Blade. --}}
            @endif
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection

{{-- Menambahkan konten ke stack tertentu pada layout. --}}
@push('scripts')
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script>
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const salesExportData = {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        transactions: {!! json_encode($transactions->map(fn ($item) => [
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'date' => optional($item->transaction_date)->format('d-m-Y H:i'),
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'customer' => $item->customer->name ?? '-',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'payment' => strtoupper($item->payment_type),
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'total' => (int) $item->total,
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        ])->values()) !!},
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        topProducts: {!! json_encode($topProducts->map(fn ($item) => [
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'product' => $item->product_name,
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'qty' => (int) $item->qty_sold,
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'total' => (int) $item->total_sales,
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        ])->values()) !!},
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        summary: {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            totalSales: {{ (int) $totalSales }},
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            totalTransactions: {{ (int) $totalTransactions }},
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            averagePerTransaction: {{ (int) $averagePerTransaction }},
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            creditSales: {{ (int) $creditSales }}
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    };

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const formatRupiah = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const createExcelSheet = (title, headers, rows, summaryRows = []) => {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const aoa = [[title], []];

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        summaryRows.forEach((row) => aoa.push(row));
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        if (summaryRows.length) {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            aoa.push([]);
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        aoa.push(headers);
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        rows.forEach((row) => aoa.push(row));

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const worksheet = XLSX.utils.aoa_to_sheet(aoa);
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const headerRowIndex = aoa.length - rows.length - 1;

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        worksheet['!autofilter'] = {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            ref: XLSX.utils.encode_range({
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                s: { r: headerRowIndex, c: 0 },
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                e: { r: headerRowIndex, c: headers.length - 1 }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            })
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        };

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        worksheet['!cols'] = headers.map((header, colIndex) => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const lengths = rows.map((row) => String(row[colIndex] ?? '').length);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const maxLength = Math.max(String(header).length, ...lengths, 12);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            return { wch: Math.min(maxLength + 2, 45) };
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        return worksheet;
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    };

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const excelBtn = document.getElementById('exportSalesExcel');
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    if (excelBtn) {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        excelBtn.addEventListener('click', () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (typeof XLSX === 'undefined') {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                window.alert('Modul Excel belum termuat. Silakan coba lagi.');
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                return;
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const transactionRows = salesExportData.transactions.map((item, index) => [
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                index + 1,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.date,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.customer,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.payment,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.total
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            ]);

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const topProductRows = salesExportData.topProducts.map((item, index) => [
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                index + 1,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.product,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.qty,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.total
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            ]);

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const workbook = XLSX.utils.book_new();
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const transactionSheet = createExcelSheet(
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                'Laporan Penjualan',
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ['No', 'Tanggal', 'Pelanggan', 'Pembayaran', 'Total'],
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                transactionRows,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                [
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Total Penjualan', salesExportData.summary.totalSales],
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Jumlah Transaksi', salesExportData.summary.totalTransactions],
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Rata-rata Transaksi', salesExportData.summary.averagePerTransaction],
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Total Utang', salesExportData.summary.creditSales]
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ]
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            );
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const topProductSheet = createExcelSheet(
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                'Top Produk Terlaris',
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ['No', 'Produk', 'Qty Terjual', 'Total'],
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                topProductRows
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            );

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            XLSX.utils.book_append_sheet(workbook, transactionSheet, 'Penjualan');
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            XLSX.utils.book_append_sheet(workbook, topProductSheet, 'Top Produk');
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            XLSX.writeFile(workbook, 'laporan-penjualan.xlsx');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    }

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const pdfBtn = document.getElementById('exportSalesPdf');
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    if (pdfBtn) {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        pdfBtn.addEventListener('click', () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const { jsPDF } = window.jspdf;
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const doc = new jsPDF({ orientation: 'landscape' });
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.setFontSize(14);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text('Laporan Penjualan', 14, 14);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.setFontSize(10);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Total Penjualan: ${formatRupiah(salesExportData.summary.totalSales)}`, 14, 22);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Jumlah Transaksi: ${salesExportData.summary.totalTransactions}`, 14, 28);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Rata-rata: ${formatRupiah(salesExportData.summary.averagePerTransaction)}`, 14, 34);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Total Utang: ${formatRupiah(salesExportData.summary.creditSales)}`, 14, 40);

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.autoTable({
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                startY: 48,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                head: [['No', 'Tanggal', 'Pelanggan', 'Pembayaran', 'Total']],
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                body: salesExportData.transactions.map((item, index) => [
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    index + 1,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.date,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.customer,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.payment,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    formatRupiah(item.total)
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ]),
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                styles: { fontSize: 8 }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            });

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const nextY = doc.lastAutoTable.finalY + 10;
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text('Top Produk Terlaris', 14, nextY);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.autoTable({
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                startY: nextY + 4,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                head: [['Produk', 'Qty', 'Total']],
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                body: salesExportData.topProducts.map((item) => [
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.product,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.qty,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    formatRupiah(item.total)
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ]),
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                styles: { fontSize: 8 }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            });

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.save('laporan-penjualan.pdf');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    }

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const chartCanvas = document.getElementById('salesChart');

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    if (chartCanvas) {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const ctx = chartCanvas.getContext('2d');

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        new Chart(ctx, {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            type: 'bar',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            data: {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                labels: {!! json_encode($chartLabels) !!},
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                datasets: [{
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    label: 'Total Penjualan (Rp)',
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    data: {!! json_encode($chartTotals) !!},
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    backgroundColor: 'rgba(28, 77, 141, 0.72)',
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    borderColor: 'rgba(28, 77, 141, 1)',
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    borderWidth: 1,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    borderRadius: 6
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                }]
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            },
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            options: {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                responsive: true,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                scales: {
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    y: {
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        beginAtZero: true,
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        ticks: {
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            callback: function(value) {
                                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                return 'Rp ' + value.toLocaleString();
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            }
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        }
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    }
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    }
{{-- Menutup blok JavaScript pada halaman ini. --}}
</script>
{{-- Menutup blok push pada template Blade. --}}
@endpush
