{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Laporan Pengeluaran')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Analisis biaya operasional dan laba/rugi')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stack-lg">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Filter Laporan Pengeluaran</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="GET" action="{{ route('reports.expenses') }}" class="form-grid">
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
                    <label for="expense_category_id">Kategori Biaya</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="expense_category_id" name="expense_category_id">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua Kategori</option>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($expenseCategories as $category)
                            {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                            <option value="{{ $category->id }}" @selected((string) request('expense_category_id') === (string) $category->id)>
                                {{-- Menampilkan data dinamis dari server ke halaman. --}}
                                {{ $category->nama }}
                            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                            </option>
                        {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                        @endforeach
                    {{-- Menutup elemen dropdown setelah seluruh pilihan ditentukan. --}}
                    </select>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field">
                    {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
                    <label for="q">Pencarian</label>
                    {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                    <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Catatan / kategori / petugas">
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>

                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="td-actions field-full">
                    {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <a href="{{ route('reports.expenses') }}" class="btn btn-secondary">Reset</a>
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
        <div class="stat-card sc-red">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Total Biaya</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Akumulasi biaya operasional</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-green">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Total Penjualan</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">{{ number_format($totalTransactions, 0, ',', '.') }} transaksi</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card {{ $netProfit >= 0 ? 'sc-blue' : 'sc-amber' }}">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Laba / Rugi</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">{{ $netProfit >= 0 ? 'Laba periode terfilter' : 'Rugi periode terfilter' }}</div>
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
                <div class="card-title">Komposisi Biaya per Kategori</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($expenseByCategory->isEmpty())
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="empty-state">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="es-icon">-</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <p>Belum ada data biaya untuk ditampilkan.</p>
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                @else
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <canvas id="expenseCategoryChart" height="120"></canvas>
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
                <div class="card-title">Ringkasan Kategori</div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-body">
                {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                @if ($expenseByCategory->isEmpty())
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="empty-state">
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <div class="es-icon">-</div>
                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                        <p>Tidak ada ringkasan kategori.</p>
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
                                <th>Kategori</th>
                                {{-- Menampilkan judul kolom pada tabel. --}}
                                <th>Nominal</th>
                            {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                            </tr>
                            {{-- Menutup bagian kepala tabel. --}}
                            </thead>
                            {{-- Membuka bagian isi tabel untuk data utama. --}}
                            <tbody>
                            {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                            @foreach ($expenseByCategory as $label => $value)
                                {{-- Membuka baris baru pada tabel. --}}
                                <tr>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td>{{ $label }}</td>
                                    {{-- Menampilkan isi sel pada tabel. --}}
                                    <td class="mono">Rp {{ number_format($value, 0, ',', '.') }}</td>
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
            <div class="card-title">Detail Pengeluaran</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($expenses->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Tidak ada data pengeluaran pada filter ini.</p>
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
                            <th>Kategori</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Nominal</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Catatan</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Petugas</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($expenses as $expense)
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $loop->iteration }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ optional($expense->tanggal)->format('d-m-Y') ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $expense->category->nama ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $expense->catatan ?: '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $expense->user->name ?? '-' }}</td>
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
<script>
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    (() => {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const labels = {!! json_encode($categoryLabels) !!};
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const values = {!! json_encode($categoryTotals) !!};

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        if (!labels.length || !values.length) {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            return;
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const canvas = document.getElementById('expenseCategoryChart');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        if (!canvas) {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            return;
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const palette = [
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'rgba(28, 77, 141, 0.82)',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'rgba(73, 136, 196, 0.82)',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'rgba(245, 158, 11, 0.82)',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'rgba(16, 185, 129, 0.82)',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'rgba(239, 68, 68, 0.82)',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'rgba(107, 114, 128, 0.82)'
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        ];

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        new Chart(canvas.getContext('2d'), {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            type: 'doughnut',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            data: {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                labels,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                datasets: [{
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    data: values,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    backgroundColor: labels.map((_, index) => palette[index % palette.length]),
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    borderColor: '#ffffff',
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    borderWidth: 1
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                }]
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            },
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            options: {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                responsive: true,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                plugins: {
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    legend: {
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        position: 'bottom'
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    },
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    tooltip: {
                        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                        callbacks: {
                            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                            label: (ctx) => `${ctx.label}: Rp ${Number(ctx.raw || 0).toLocaleString('id-ID')}`
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
    })();
{{-- Menutup blok JavaScript pada halaman ini. --}}
</script>
{{-- Menutup blok push pada template Blade. --}}
@endpush
