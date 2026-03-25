@extends('layouts.admin')

@section('title', 'Laporan Utang')
@section('subtitle', 'Analisis data piutang pelanggan')

@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Laporan Utang</div>
            <div class="td-actions">
                <button type="button" class="btn btn-secondary" id="exportReceivablesExcel">Unduh Excel</button>
                <button type="button" class="btn btn-outline" id="exportReceivablesPdf">Unduh PDF</button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.receivables') }}" class="form-grid">
                <div class="field">
                    <label for="start_date">Dari Tanggal</label>
                    <input id="start_date" type="date" name="start_date" value="{{ request('start_date') }}">
                </div>

                <div class="field">
                    <label for="end_date">Sampai Tanggal</label>
                    <input id="end_date" type="date" name="end_date" value="{{ request('end_date') }}">
                </div>

                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="unpaid" @selected(request('status') === 'unpaid')>Belum Lunas</option>
                        <option value="paid" @selected(request('status') === 'paid')>Lunas</option>
                    </select>
                </div>

                <div class="field">
                    <label for="customer_id">Pelanggan</label>
                    <select id="customer_id" name="customer_id">
                        <option value="">Semua Pelanggan</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected((string) request('customer_id') === (string) $customer->id)>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="td-actions field-full">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('reports.receivables') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card sc-red">
            <div class="sc-label">Total Belum Lunas</div>
            <div class="sc-value mono">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</div>
            <div class="sc-sub">Piutang berjalan</div>
        </div>
        <div class="stat-card sc-green">
            <div class="sc-label">Total Sudah Lunas</div>
            <div class="sc-value mono">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            <div class="sc-sub">Sudah dibayar pelanggan</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="sc-label">Lewat Jatuh Tempo</div>
            <div class="sc-value mono">Rp {{ number_format($overdueUnpaid, 0, ',', '.') }}</div>
            <div class="sc-sub">Perlu ditindaklanjuti</div>
        </div>
    </div>

    <div class="card">
        <div class="card-hd">
            <div class="card-title">Detail Piutang</div>
        </div>
        <div class="card-body">
            @if ($receivables->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada data piutang pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Transaksi</th>
                            <th>Tanggal Utang</th>
                            <th>Jatuh Tempo</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tgl Lunas</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($receivables as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->transaction->customer->name ?? '-' }}</td>
                                <td>#{{ $item->transaction_id }}</td>
                                <td>{{ optional($item->created_at)->format('d-m-Y') ?? '-' }}</td>
                                <td>{{ optional($item->due_date)->format('d-m-Y') ?? '-' }}</td>
                                <td class="mono">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if ($item->status === 'paid')
                                        <span class="badge badge-green">Lunas</span>
                                    @else
                                        <span class="badge badge-amber">Belum Lunas</span>
                                    @endif
                                </td>
                                <td>{{ optional($item->paid_at)->format('d-m-Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    const receivableExportData = {
        rows: {!! json_encode($receivables->map(fn ($item) => [
            'customer' => $item->transaction->customer->name ?? '-',
            'transaction_id' => (int) $item->transaction_id,
            'created_at' => optional($item->created_at)->format('d-m-Y') ?? '-',
            'due_date' => optional($item->due_date)->format('d-m-Y') ?? '-',
            'amount' => (int) $item->amount,
            'status' => $item->status === 'paid' ? 'Lunas' : 'Belum Lunas',
            'paid_at' => optional($item->paid_at)->format('d-m-Y H:i') ?? '-',
        ])->values()) !!},
        summary: {
            totalUnpaid: {{ (int) $totalUnpaid }},
            totalPaid: {{ (int) $totalPaid }},
            overdueUnpaid: {{ (int) $overdueUnpaid }}
        }
    };

    const formatRupiahReceivable = (value) => `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;

    const createExcelSheet = (title, headers, rows, summaryRows = []) => {
        const aoa = [[title], []];

        summaryRows.forEach((row) => aoa.push(row));
        if (summaryRows.length) {
            aoa.push([]);
        }

        aoa.push(headers);
        rows.forEach((row) => aoa.push(row));

        const worksheet = XLSX.utils.aoa_to_sheet(aoa);
        const headerRowIndex = aoa.length - rows.length - 1;

        worksheet['!autofilter'] = {
            ref: XLSX.utils.encode_range({
                s: { r: headerRowIndex, c: 0 },
                e: { r: headerRowIndex, c: headers.length - 1 }
            })
        };

        worksheet['!cols'] = headers.map((header, colIndex) => {
            const lengths = rows.map((row) => String(row[colIndex] ?? '').length);
            const maxLength = Math.max(String(header).length, ...lengths, 12);
            return { wch: Math.min(maxLength + 2, 45) };
        });

        return worksheet;
    };

    const receivableExcelBtn = document.getElementById('exportReceivablesExcel');
    if (receivableExcelBtn) {
        receivableExcelBtn.addEventListener('click', () => {
            if (typeof XLSX === 'undefined') {
                window.alert('Modul Excel belum termuat. Silakan coba lagi.');
                return;
            }

            const rows = receivableExportData.rows.map((item, index) => [
                index + 1,
                item.customer,
                `#${item.transaction_id}`,
                item.created_at,
                item.due_date,
                item.amount,
                item.status,
                item.paid_at
            ]);

            const worksheet = createExcelSheet(
                'Laporan Utang',
                ['No', 'Pelanggan', 'Transaksi', 'Tanggal Utang', 'Jatuh Tempo', 'Jumlah', 'Status', 'Tgl Lunas'],
                rows,
                [
                    ['Total Belum Lunas', receivableExportData.summary.totalUnpaid],
                    ['Total Sudah Lunas', receivableExportData.summary.totalPaid],
                    ['Lewat Jatuh Tempo', receivableExportData.summary.overdueUnpaid]
                ]
            );

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Utang');
            XLSX.writeFile(workbook, 'laporan-utang.xlsx');
        });
    }

    const receivablePdfBtn = document.getElementById('exportReceivablesPdf');
    if (receivablePdfBtn) {
        receivablePdfBtn.addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape' });
            doc.setFontSize(14);
            doc.text('Laporan Utang', 14, 14);
            doc.setFontSize(10);
            doc.text(`Total Belum Lunas: ${formatRupiahReceivable(receivableExportData.summary.totalUnpaid)}`, 14, 22);
            doc.text(`Total Sudah Lunas: ${formatRupiahReceivable(receivableExportData.summary.totalPaid)}`, 14, 28);
            doc.text(`Lewat Jatuh Tempo: ${formatRupiahReceivable(receivableExportData.summary.overdueUnpaid)}`, 14, 34);

            doc.autoTable({
                startY: 42,
                head: [['No', 'Pelanggan', 'Transaksi', 'Tanggal Utang', 'Jatuh Tempo', 'Jumlah', 'Status', 'Tgl Lunas']],
                body: receivableExportData.rows.map((item, index) => [
                    index + 1,
                    item.customer,
                    `#${item.transaction_id}`,
                    item.created_at,
                    item.due_date,
                    formatRupiahReceivable(item.amount),
                    item.status,
                    item.paid_at
                ]),
                styles: { fontSize: 8 }
            });

            doc.save('laporan-utang.pdf');
        });
    }
</script>
@endpush
