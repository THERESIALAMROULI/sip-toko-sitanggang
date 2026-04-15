@extends('layouts.admin')
@section('title', $correctionSource ? 'Buat Koreksi Stok' : 'Riwayat Stok')
@section('subtitle', $correctionSource ? 'Tambah koreksi stok' : 'Tambah stok masuk')
@section('content')
<div class="card">
    <div class="card-hd">
        <div class="card-title">{{ $correctionSource ? 'Koreksi Stok' : 'Stok Masuk' }}</div>
    </div>
    <div class="card-body">
        @if ($correctionSource)
            <div class="alert alert-info">
                Koreksi untuk catatan {{ optional($correctionSource->tanggal)->format('d/m/Y H:i') ?? '-' }} pada produk {{ $correctionSource->produk->nama ?? '-' }}.
            </div>
        @else
            <div class="alert alert-info">
                Gunakan form ini untuk mencatat barang masuk.
            </div>
        @endif

        <form method="POST" action="{{ route('stok_histories.store') }}" class="stack-md">
            @csrf
            @if ($correctionSource)
                <input type="hidden" name="referensi_mutasi_id" value="{{ $correctionSource->id }}">
            @else
                <input type="hidden" name="jenis" value="masuk">
            @endif
            <div class="form-grid">
                @if ($correctionSource)
                    <div class="field">
                        <label for="jenis">Jenis koreksi</label>
                        <select id="jenis" name="jenis" required>
                            @foreach ($mutationOptions as $option)
                                <option value="{{ $option['value'] }}" @selected(old('jenis', $formDefaults['jenis']) === $option['value'])>{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                        <div class="form-hint">
                            @foreach ($mutationOptions as $option)
                                <div><strong>{{ $option['label'] }}:</strong> {{ $option['description'] }}</div>
                            @endforeach
                        </div>
                        @error('jenis')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <div class="field">
                        <label>Jenis riwayat</label>
                        <input type="text" value="Barang datang" disabled>
                        <div class="form-hint">Selalu tercatat sebagai barang masuk.</div>
                    </div>
                @endif
                <div class="field">
                    <label for="produk_id">Produk</label>
                    <select id="produk_id" name="produk_id" required>
                        <option value="">Pilih produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected((int) old('produk_id', $formDefaults['produk_id']) === $product->id)>
                                {{ $product->nama }} (stok: {{ $product->stok }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint">Pilih produk yang ingin dicatat.</div>
                    @error('produk_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="supplier_id">Pemasok</label>
                    <select id="supplier_id" name="supplier_id">
                        <option value="">Tanpa supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected((int) old('supplier_id', $formDefaults['supplier_id']) === $supplier->id)>{{ $supplier->nama }}</option>
                        @endforeach
                    </select>
                    <div class="form-hint">{{ $correctionSource ? 'Isi jika terkait pemasok.' : 'Isi jika barang datang dari pemasok.' }}</div>
                    @error('supplier_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="jumlah">Jumlah</label>
                    <input id="jumlah" type="number" name="jumlah" value="{{ old('jumlah', $formDefaults['jumlah']) }}" min="1" required>
                    <div class="form-hint">Isi angka saja.</div>
                    @error('jumlah')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field field-full">
                    <label for="keterangan">Catatan tambahan</label>
                    <textarea id="keterangan" name="keterangan" placeholder="Contoh: Barang datang dari supplier atau hasil hitung ulang stok">{{ old('keterangan', $formDefaults['keterangan']) }}</textarea>
                    <div class="form-hint">Tulis catatan singkat.</div>
                    @error('keterangan')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="td-actions">
                <button type="submit" class="btn btn-primary">{{ $correctionSource ? 'Simpan Koreksi' : 'Simpan' }}</button>
                <a href="{{ route('stok_histories.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
