@extends('layouts.admin')
@section('title', 'Biaya Operasional')
@section('subtitle', 'Data pengeluaran')
@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Biaya</div>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">+ Tambah Biaya</a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('expenses.index') }}" class="form-grid">
                <div class="field">
                    <label for="q">Pencarian</label>
                    <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Catatan / kategori / petugas">
                </div>
                <div class="field">
                    <label for="expense_category_id">Kategori</label>
                    <select id="expense_category_id" name="expense_category_id">
                        <option value="">Semua Kategori</option>
                        @foreach ($expenseCategories as $category)
                            <option value="{{ $category->id }}" @selected((string) ($filters['expense_category_id'] ?? '') === (string) $category->id)>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="start_date">Dari Tanggal</label>
                    <input id="start_date" type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
                </div>
                <div class="field">
                    <label for="end_date">Sampai Tanggal</label>
                    <input id="end_date" type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}">
                </div>
                <div class="td-actions field-full">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="stat-grid">
        <div class="stat-card sc-red">
            <div class="sc-label">Total Biaya</div>
            <div class="sc-value mono">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            <div class="sc-sub">{{ number_format($expenses->count(), 0, ',', '.') }} data biaya</div>
        </div>
    </div>
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Daftar Biaya Operasional</div>
        </div>
        <div class="card-body">
            @if ($expenses->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Tidak ada data biaya pada filter ini.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Nominal</th>
                            <th>Catatan</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($expense->tanggal)->format('d-m-Y') ?? '-' }}</td>
                                <td>{{ $expense->category->nama ?? '-' }}</td>
                                <td class="mono">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</td>
                                <td>{{ $expense->catatan ?: '-' }}</td>
                                <td>{{ $expense->user->name ?? '-' }}</td>
                                <td>
                                    <div class="td-actions">
                                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data biaya ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
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
