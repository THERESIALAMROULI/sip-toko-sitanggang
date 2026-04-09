{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Laporan Stok')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Ringkasan persediaan dan mutasi stok')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stack-lg">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Filter Laporan Stok</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="td-actions">
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="button" class="btn btn-secondary" id="exportStockExcel">Unduh Excel</button>
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="button" class="btn btn-outline" id="exportStockPdf">Unduh PDF</button>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="GET" action="{{ route('reports.stock') }}" class="search-row">
                {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                <input type="text" name="q" class="search-input" placeholder="Cari nama produk..." value="{{ request('q') }}">

                {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                <select name="kategori_id" class="filter-sel">
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="">Semua Kategori</option>
                    {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                    @foreach ($kategoris as $kategori)
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="{{ $kategori->id }}" @selected((string) request('kategori_id') === (string) $kategori->id)>{{ $kategori->nama }}</option>
                    {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                    @endforeach
                {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                </select>

                {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                <select name="status" class="filter-sel">
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="">Semua Status</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="normal" @selected(request('status') === 'normal')>Normal</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="low" @selected(request('status') === 'low')>Hampir Habis</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="out" @selected(request('status') === 'out')>Habis</option>
                    {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                </select>

                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="submit" class="btn btn-secondary">Filter</button>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('reports.stock') }}" class="btn btn-secondary">Reset</a>
            {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
            </form>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="stat-grid">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-blue">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Total Produk</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Semua data produk</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-amber">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Hampir Habis</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value">{{ number_format($lowStockCount, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Perlu restok</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-red">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Stok Habis</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value">{{ number_format($outStockCount, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Tidak tersedia</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-green">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Nilai Persediaan</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($stockValue, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Estimasi berdasarkan harga beli</div>
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
                <div class="card-title">Detail Stok Produk</div>
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
                        <p>Tidak ada produk sesuai filter.</p>
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
                                <th>Kategori</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Stok</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Min</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Status</th>
                            {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                            </tr>
                            {{-- Menutup bagian kepala tabel. --}}
                            </thead>
                            {{-- Membuka bagian isi tabel untuk data utama. --}}
                            <tbody>
                            {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                            @foreach ($products as $product)
                                {{-- Membuka blok PHP pada template Blade. --}}
                                @php
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    $status = 'Normal';
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    $badge = 'badge-green';

                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    if (! $product->aktif) {
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        $status = 'Nonaktif';
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        $badge = 'badge-gray';
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    } elseif ((int) $product->stok <= 0) {
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        $status = 'Habis';
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        $badge = 'badge-red';
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    } elseif ((int) $product->stok <= (int) $product->stok_minimum) {
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        $status = 'Hampir Habis';
                                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                        $badge = 'badge-amber';
                                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                                    }
                                {{-- Menutup blok PHP pada template Blade. --}}
                                @endphp
                                {{-- Membuka baris baru pada tabel. --}}
                                <tr>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $product->nama }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $product->kategori->nama ?? '-' }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td class="mono">{{ $product->stok }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td class="mono">{{ $product->stok_minimum }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
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

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-hd">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="card-title">Mutasi Stok Terbaru</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($recentMutations->isEmpty())
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="empty-state">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="es-icon">-</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <p>Belum ada mutasi stok.</p>
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
                                <th>Tanggal</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Produk</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Jumlah</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Petugas</th>
                            {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                            </tr>
                            {{-- Menutup bagian kepala tabel. --}}
                            </thead>
                            {{-- Membuka bagian isi tabel untuk data utama. --}}
                            <tbody>
                            {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                            @foreach ($recentMutations as $mutation)
                                {{-- Membuka baris baru pada tabel. --}}
                                <tr>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ optional($mutation->tanggal)->format('d-m-Y H:i') ?? '-' }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $mutation->produk->nama ?? '-' }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td class="mono">{{ $mutation->jumlah > 0 ? '+' : '' }}{{ $mutation->jumlah }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $mutation->user->name ?? '-' }}</td>
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
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup section Blade yang sedang didefinisikan. --}}
@endsection

{{-- Menambahkan konten ke stack tertentu pada layout. --}}
@push('scripts')
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script>
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const stockExportData = {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        products: {!! json_encode($products->map(function ($product) {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            $status = 'Normal';
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (! $product->aktif) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                $status = 'Nonaktif';
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            } elseif ((int) $product->stok <= 0) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                $status = 'Habis';
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            } elseif ((int) $product->stok <= (int) $product->stok_minimum) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                $status = 'Hampir Habis';
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            return [
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                'name' => $product->nama,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                'category' => $product->kategori->nama ?? '-',
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                'stock' => (int) $product->stok,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                'min_stock' => (int) $product->stok_minimum,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                'status' => $status,
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            ];
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        })->values()) !!},
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        summary: {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            totalProducts: {{ (int) $totalProducts }},
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            lowStockCount: {{ (int) $lowStockCount }},
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            outStockCount: {{ (int) $outStockCount }},
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            stockValue: {{ (int) $stockValue }}
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    };

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const formatRupiahStock = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;

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
    const stockExcelBtn = document.getElementById('exportStockExcel');
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    if (stockExcelBtn) {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        stockExcelBtn.addEventListener('click', () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (typeof XLSX === 'undefined') {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                window.alert('Modul Excel belum termuat. Silakan coba lagi.');
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                return;
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const rows = stockExportData.products.map((item, index) => [
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                index + 1,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.name,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.category,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.stock,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.min_stock,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.status
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            ]);

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const worksheet = createExcelSheet(
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                'Laporan Stok',
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ['No', 'Produk', 'Kategori', 'Stok', 'Stok Minimum', 'Status'],
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                rows,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                [
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Total Produk', stockExportData.summary.totalProducts],
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Hampir Habis', stockExportData.summary.lowStockCount],
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Stok Habis', stockExportData.summary.outStockCount],
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Nilai Persediaan', stockExportData.summary.stockValue]
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ]
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            );

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const workbook = XLSX.utils.book_new();
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Stok');
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            XLSX.writeFile(workbook, 'laporan-stok.xlsx');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    }

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const stockPdfBtn = document.getElementById('exportStockPdf');
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    if (stockPdfBtn) {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        stockPdfBtn.addEventListener('click', () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const { jsPDF } = window.jspdf;
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const doc = new jsPDF({ orientation: 'landscape' });
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.setFontSize(14);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text('Laporan Stok', 14, 14);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.setFontSize(10);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Total Produk: ${stockExportData.summary.totalProducts}`, 14, 22);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Hampir Habis: ${stockExportData.summary.lowStockCount}`, 14, 28);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Stok Habis: ${stockExportData.summary.outStockCount}`, 14, 34);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Nilai Persediaan: ${formatRupiahStock(stockExportData.summary.stockValue)}`, 14, 40);

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.autoTable({
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                startY: 48,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                head: [['No', 'Produk', 'Kategori', 'Stok', 'Stok Min', 'Status']],
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                body: stockExportData.products.map((item, index) => [
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    index + 1,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.name,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.category,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.stock,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.min_stock,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.status
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ]),
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                styles: { fontSize: 8 }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            });

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.save('laporan-stok.pdf');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    }
{{-- Menutup blok JavaScript pada halaman ini. --}}
</script>
{{-- Menutup blok push pada template Blade. --}}
@endpush
