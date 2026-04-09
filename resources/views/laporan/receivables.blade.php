{{-- Menggunakan layout utama agar struktur halaman tetap konsisten. --}}
@extends('layouts.admin')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('title', 'Laporan Utang')
{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('subtitle', 'Analisis data piutang pelanggan')

{{-- Mendefinisikan bagian halaman yang akan diisi pada layout. --}}
@section('content')
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="stack-lg">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Filter Laporan Utang</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="td-actions">
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="button" class="btn btn-secondary" id="exportReceivablesExcel">Unduh Excel</button>
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="button" class="btn btn-outline" id="exportReceivablesPdf">Unduh PDF</button>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="GET" action="{{ route('reports.receivables') }}" class="form-grid">
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
                    <label for="status">Status</label>
                    {{-- Membuka pilihan dropdown untuk data yang sudah disediakan. --}}
                    <select id="status" name="status">
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="">Semua</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="unpaid" @selected(request('status') === 'unpaid')>Belum Lunas</option>
                        {{-- Menampilkan salah satu pilihan pada elemen dropdown. --}}
                        <option value="paid" @selected(request('status') === 'paid')>Lunas</option>
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
                    <a href="{{ route('reports.receivables') }}" class="btn btn-secondary">Reset</a>
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
            <div class="sc-label">Total Belum Lunas</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Piutang berjalan</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-green">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Total Sudah Lunas</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Sudah dibayar pelanggan</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="stat-card sc-amber">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-label">Lewat Jatuh Tempo</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-value mono">Rp {{ number_format($overdueUnpaid, 0, ',', '.') }}</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sc-sub">Perlu ditindaklanjuti</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-hd">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="card-title">Detail Piutang</div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="card-body">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($receivables->isEmpty())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="empty-state">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="es-icon">-</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <p>Tidak ada data piutang pada filter ini.</p>
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
                            <th>Pelanggan</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Transaksi</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Tanggal Utang</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Jatuh Tempo</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Jumlah</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Status</th>
                            {{-- Menampilkan judul kolom pada tabel. --}}
                            <th>Tgl Lunas</th>
                        {{-- Menutup baris tabel yang sedang ditampilkan. --}}
                        </tr>
                        {{-- Menutup bagian kepala tabel. --}}
                        </thead>
                        {{-- Membuka bagian isi tabel untuk data utama. --}}
                        <tbody>
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($receivables as $item)
                            {{-- Membuka baris baru pada tabel. --}}
                            <tr>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $loop->iteration }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ $item->transaction->customer->name ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>#{{ $item->transaction_id }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ optional($item->created_at)->format('d-m-Y') ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ optional($item->due_date)->format('d-m-Y') ?? '-' }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td class="mono">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>
                                    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
                                    @if ($item->status === 'paid')
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-green">Lunas</span>
                                    {{-- Menampilkan alternatif ketika kondisi sebelumnya tidak terpenuhi. --}}
                                    @else
                                        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                                        <span class="badge badge-amber">Belum Lunas</span>
                                    {{-- Menutup percabangan kondisi pada template Blade. --}}
                                    @endif
                                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                                </td>
                                {{-- Menampilkan isi sel pada tabel. --}}
                                <td>{{ optional($item->paid_at)->format('d-m-Y H:i') ?? '-' }}</td>
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
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script>
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const receivableExportData = {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        rows: {!! json_encode($receivables->map(fn ($item) => [
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'customer' => $item->transaction->customer->name ?? '-',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'transaction_id' => (int) $item->transaction_id,
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'created_at' => optional($item->created_at)->format('d-m-Y') ?? '-',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'due_date' => optional($item->due_date)->format('d-m-Y') ?? '-',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'amount' => (int) $item->amount,
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'status' => $item->status === 'paid' ? 'Lunas' : 'Belum Lunas',
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            'paid_at' => optional($item->paid_at)->format('d-m-Y H:i') ?? '-',
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        ])->values()) !!},
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        summary: {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            totalUnpaid: {{ (int) $totalUnpaid }},
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            totalPaid: {{ (int) $totalPaid }},
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            overdueUnpaid: {{ (int) $overdueUnpaid }}
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    };

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const formatRupiahReceivable = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;

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
    const receivableExcelBtn = document.getElementById('exportReceivablesExcel');
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    if (receivableExcelBtn) {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        receivableExcelBtn.addEventListener('click', () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (typeof XLSX === 'undefined') {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                window.alert('Modul Excel belum termuat. Silakan coba lagi.');
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                return;
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const rows = receivableExportData.rows.map((item, index) => [
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                index + 1,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.customer,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                `#${item.transaction_id}`,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.created_at,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.due_date,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.amount,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.status,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                item.paid_at
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            ]);

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const worksheet = createExcelSheet(
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                'Laporan Utang',
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ['No', 'Pelanggan', 'Transaksi', 'Tanggal Utang', 'Jatuh Tempo', 'Jumlah', 'Status', 'Tgl Lunas'],
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                rows,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                [
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Total Belum Lunas', receivableExportData.summary.totalUnpaid],
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Total Sudah Lunas', receivableExportData.summary.totalPaid],
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    ['Lewat Jatuh Tempo', receivableExportData.summary.overdueUnpaid]
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ]
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            );

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const workbook = XLSX.utils.book_new();
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Utang');
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            XLSX.writeFile(workbook, 'laporan-utang.xlsx');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    }

    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    const receivablePdfBtn = document.getElementById('exportReceivablesPdf');
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    if (receivablePdfBtn) {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        receivablePdfBtn.addEventListener('click', () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const { jsPDF } = window.jspdf;
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            const doc = new jsPDF({ orientation: 'landscape' });
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.setFontSize(14);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text('Laporan Utang', 14, 14);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.setFontSize(10);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Total Belum Lunas: ${formatRupiahReceivable(receivableExportData.summary.totalUnpaid)}`, 14, 22);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Total Sudah Lunas: ${formatRupiahReceivable(receivableExportData.summary.totalPaid)}`, 14, 28);
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.text(`Lewat Jatuh Tempo: ${formatRupiahReceivable(receivableExportData.summary.overdueUnpaid)}`, 14, 34);

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.autoTable({
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                startY: 42,
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                head: [['No', 'Pelanggan', 'Transaksi', 'Tanggal Utang', 'Jatuh Tempo', 'Jumlah', 'Status', 'Tgl Lunas']],
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                body: receivableExportData.rows.map((item, index) => [
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    index + 1,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.customer,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    `#${item.transaction_id}`,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.created_at,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.due_date,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    formatRupiahReceivable(item.amount),
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.status,
                    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                    item.paid_at
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                ]),
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                styles: { fontSize: 8 }
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            });

            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            doc.save('laporan-utang.pdf');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    }
{{-- Menutup blok JavaScript pada halaman ini. --}}
</script>
{{-- Menutup blok push pada template Blade. --}}
@endpush
