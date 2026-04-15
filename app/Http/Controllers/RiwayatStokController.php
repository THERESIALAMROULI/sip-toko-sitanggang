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
        $mutationOptions = $this->incomingMutationOptions();
        $correctionSource = null;
        $formDefaults = [
            'jenis' => 'masuk',
            'produk_id' => null,
            'supplier_id' => null,
            'jumlah' => null,
            'keterangan' => null,
        ];

        return view('stok_histories.create', compact('products', 'suppliers', 'mutationOptions', 'correctionSource', 'formDefaults'));
    }

    public function createCorrection(RiwayatStok $stokHistory)
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
        $mutationOptions = $this->correctionMutationOptions();
        $correctionSource = $stokHistory->load(['produk', 'supplier', 'user']);
        $formDefaults = [
            'jenis' => $stokHistory->jumlah < 0 ? 'koreksi_tambah' : 'koreksi_kurang',
            'produk_id' => $stokHistory->produk_id,
            'supplier_id' => $stokHistory->supplier_id,
            'jumlah' => abs((int) $stokHistory->jumlah),
            'keterangan' => null,
        ];

        return view('stok_histories.create', compact('products', 'suppliers', 'mutationOptions', 'correctionSource', 'formDefaults'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => ['required', 'in:masuk,koreksi_tambah,koreksi_kurang'],
            'produk_id' => ['required', 'integer', 'exists:produks,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'referensi_mutasi_id' => ['nullable', 'integer', 'exists:stok_histories,id'],
        ]);
        $isCorrection = ! empty($validated['referensi_mutasi_id']);

        if ($isCorrection && ! in_array($validated['jenis'], ['koreksi_tambah', 'koreksi_kurang'], true)) {
            throw ValidationException::withMessages([
                'jenis' => 'Form koreksi hanya boleh dipakai untuk perbaikan stok tambah atau kurangi.',
            ]);
        }

        if (! $isCorrection && $validated['jenis'] !== 'masuk') {
            throw ValidationException::withMessages([
                'jenis' => 'Form riwayat stok hanya dipakai untuk mencatat barang datang.',
            ]);
        }

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

        $message = ! empty($validated['referensi_mutasi_id'])
            ? 'Koreksi stok berhasil disimpan tanpa mengubah riwayat lama.'
            : 'Riwayat stok berhasil disimpan.';

        return redirect()->route('stok_histories.index')
            ->with('success', $message);
    }

    private function signedQty(string $jenis, int $jumlah): int
    {
        return $jenis === 'koreksi_kurang' ? -1 * $jumlah : $jumlah;
    }

    private function incomingMutationOptions(): array
    {
        return [
            [
                'value' => 'masuk',
                'label' => 'Barang datang',
                'description' => 'Pakai saat toko menerima barang baru dari supplier.',
            ],
        ];
    }

    private function correctionMutationOptions(): array
    {
        return [
            [
                'value' => 'koreksi_tambah',
                'label' => 'Perbaikan stok: tambah',
                'description' => 'Pakai saat stok fisik ternyata lebih banyak dari catatan.',
            ],
            [
                'value' => 'koreksi_kurang',
                'label' => 'Perbaikan stok: kurangi',
                'description' => 'Pakai saat stok fisik ternyata lebih sedikit dari catatan.',
            ],
        ];
    }
}
