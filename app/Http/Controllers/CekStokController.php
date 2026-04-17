<?php
namespace App\Http\Controllers;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
class CekStokController extends Controller
{
    // Halaman cek stok membantu kasir memantau ketersediaan barang berdasarkan pencarian, kategori, dan status stok.
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori_id' => ['nullable', 'integer', 'exists:kategoris,id'],
            'status' => ['nullable', 'in:all,habis,menipis,normal'],
        ]);
        $selectedStatus = $validated['status'] ?? 'all';
        $productsQuery = Produk::query()
            ->with('kategori');
        // Filter ini memudahkan pencarian produk tertentu secara cepat saat operasional berlangsung.
        if (! empty($validated['q'])) {
            $search = trim($validated['q']);
            $productsQuery->where('nama', 'like', '%'.$search.'%');
        }
        if (! empty($validated['kategori_id'])) {
            $productsQuery->where('kategori_id', (int) $validated['kategori_id']);
        }
        // Status stok membedakan barang normal, menipis, habis, atau tidak aktif.
        if ($selectedStatus === 'habis') {
            $productsQuery->where('stok', '<=', 0);
        } elseif ($selectedStatus === 'menipis') {
            $productsQuery
                ->where('stok', '>', 0)
                ->whereColumn('stok', '<=', 'stok_minimum');
        } elseif ($selectedStatus === 'normal') {
            $productsQuery->whereColumn('stok', '>', 'stok_minimum');
        }

        $stockGroups = collect([
            [
                'key' => 'all',
                'label' => 'Semua',
                'count' => Produk::query()->count(),
            ],
            [
                'key' => 'habis',
                'label' => 'Habis',
                'count' => Produk::query()->where('stok', '<=', 0)->count(),
            ],
            [
                'key' => 'menipis',
                'label' => 'Menipis',
                'count' => Produk::query()
                    ->where('stok', '>', 0)
                    ->whereColumn('stok', '<=', 'stok_minimum')
                    ->count(),
            ],
            [
                'key' => 'normal',
                'label' => 'Normal',
                'count' => Produk::query()
                    ->whereColumn('stok', '>', 'stok_minimum')
                    ->count(),
            ],
        ]);

        $products = $productsQuery
            ->orderBy('nama')
            ->paginate(5)
            ->withQueryString()
            ->through(function (Produk $product) {
                if ((int) $product->stok <= 0) {
                    $product->stock_status_label = 'Habis';
                    $product->stock_badge = 'badge-red';
                } elseif ((int) $product->stok <= (int) $product->stok_minimum) {
                    $product->stock_status_label = 'Menipis';
                    $product->stock_badge = 'badge-amber';
                } else {
                    $product->stock_status_label = 'Normal';
                    $product->stock_badge = 'badge-green';
                }

                return $product;
            });
        $kategoris = Kategori::query()
            ->orderBy('nama')
            ->get();
        $filters = [
            'q' => $validated['q'] ?? null,
            'kategori_id' => $validated['kategori_id'] ?? null,
            'status' => $selectedStatus,
        ];
        // Data produk, kategori, dan filter lama dikirim agar tampilan stok tetap informatif.
        return view('stok.check', compact('products', 'kategoris', 'filters', 'stockGroups', 'selectedStatus'));
    }
}
