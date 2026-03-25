<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\RiwayatStok;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RiwayatStokController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => ['nullable', 'integer', 'exists:produks,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $stokHistoriesQuery = RiwayatStok::query()
            ->with(['produk', 'supplier', 'user']);

        if (! empty($validated['produk_id'])) {
            $stokHistoriesQuery->where('produk_id', $validated['produk_id']);
        }

        if (! empty($validated['supplier_id'])) {
            $stokHistoriesQuery->where('supplier_id', $validated['supplier_id']);
        }

        if (! empty($validated['start_date'])) {
            $stokHistoriesQuery->where('tanggal', '>=', $validated['start_date'].' 00:00:00');
        }

        if (! empty($validated['end_date'])) {
            $stokHistoriesQuery->where('tanggal', '<=', $validated['end_date'].' 23:59:59');
        }

        $stokHistories = $stokHistoriesQuery
            ->orderByDesc('tanggal')
            ->get();

        $products = Produk::query()
            ->orderBy('nama')
            ->get();

        $suppliers = Pemasok::query()
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        $filters = [
            'produk_id' => $validated['produk_id'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ];

        return view('stok_histories.index', compact('stokHistories', 'products', 'suppliers', 'filters'));
    }

    public function create()
    {
        $products = Produk::query()
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        $suppliers = Pemasok::query()
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('stok_histories.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => ['required', 'in:masuk,koreksi_tambah,koreksi_kurang'],
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $product = Produk::query()
                ->whereKey($validated['produk_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $signedQty = $this->signedQty($validated['jenis'], (int) $validated['jumlah']);

            $stokSebelum = (int) $product->stok;
            $stokSesudah = $stokSebelum + $signedQty;

            if ($stokSesudah < 0) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Koreksi mengurangi stok melebihi stok yang tersedia.',
                ]);
            }

            $product->update([
                'stok' => $stokSesudah,
            ]);

            RiwayatStok::create([
                'produk_id' => $product->id,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'user_id' => auth()->id() ?? 1,
                'jumlah' => $signedQty,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal' => now(),
            ]);
        });

        return redirect()->route('stok_histories.index')
            ->with('success', 'Mutasi stok berhasil disimpan.');
    }

    public function edit(RiwayatStok $stokHistory)
    {
        $products = Produk::query()
            ->where(function ($query) use ($stokHistory) {
                $query->where('aktif', true)
                    ->orWhere('id', $stokHistory->produk_id);
            })
            ->orderBy('nama')
            ->get();

        $suppliers = Pemasok::query()
            ->where(function ($query) use ($stokHistory) {
                $query->where('aktif', true)
                    ->orWhere('id', $stokHistory->supplier_id);
            })
            ->orderBy('nama')
            ->get();

        return view('stok_histories.edit', compact('stokHistory', 'products', 'suppliers'));
    }

    public function update(Request $request, RiwayatStok $stokHistory)
    {
        $validated = $request->validate([
            'jenis' => ['required', 'in:masuk,koreksi_tambah,koreksi_kurang'],
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $stokHistory) {
            $history = RiwayatStok::query()
                ->whereKey($stokHistory->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldProduct = Produk::query()
                ->whereKey($history->produk_id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldSignedQty = (int) $history->jumlah;
            $revertedOldStock = (int) $oldProduct->stok - $oldSignedQty;

            if ($revertedOldStock < 0) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Data stok saat ini tidak konsisten, perubahan tidak dapat diproses.',
                ]);
            }

            $newSignedQty = $this->signedQty($validated['jenis'], (int) $validated['jumlah']);

            if ((int) $validated['produk_id'] === (int) $history->produk_id) {
                $newStock = $revertedOldStock + $newSignedQty;

                if ($newStock < 0) {
                    throw ValidationException::withMessages([
                        'jumlah' => 'Koreksi mengurangi stok melebihi stok yang tersedia.',
                    ]);
                }

                $oldProduct->update(['stok' => $newStock]);

                $stokSebelum = $revertedOldStock;
                $stokSesudah = $newStock;
            } else {
                $oldProduct->update(['stok' => $revertedOldStock]);

                $newProduct = Produk::query()
                    ->whereKey($validated['produk_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $stokSebelum = (int) $newProduct->stok;
                $stokSesudah = $stokSebelum + $newSignedQty;

                if ($stokSesudah < 0) {
                    throw ValidationException::withMessages([
                        'jumlah' => 'Koreksi mengurangi stok melebihi stok yang tersedia pada produk terpilih.',
                    ]);
                }

                $newProduct->update(['stok' => $stokSesudah]);
            }

            $history->update([
                'produk_id' => (int) $validated['produk_id'],
                'supplier_id' => $validated['supplier_id'] ?? null,
                'jumlah' => $newSignedQty,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);
        });

        return redirect()->route('stok_histories.index')
            ->with('success', 'Mutasi stok berhasil diperbarui.');
    }

    public function destroy(RiwayatStok $stokHistory)
    {
        DB::transaction(function () use ($stokHistory) {
            $history = RiwayatStok::query()
                ->whereKey($stokHistory->id)
                ->lockForUpdate()
                ->firstOrFail();

            $product = Produk::query()
                ->whereKey($history->produk_id)
                ->lockForUpdate()
                ->firstOrFail();

            $revertedStock = (int) $product->stok - (int) $history->jumlah;

            if ($revertedStock < 0) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Mutasi tidak dapat dihapus karena menyebabkan stok produk menjadi negatif.',
                ]);
            }

            $product->update(['stok' => $revertedStock]);
            $history->delete();
        });

        return redirect()->route('stok_histories.index')
            ->with('success', 'Mutasi stok berhasil dihapus.');
    }

    private function signedQty(string $jenis, int $jumlah): int
    {
        return $jenis === 'koreksi_kurang' ? -1 * $jumlah : $jumlah;
    }
}
