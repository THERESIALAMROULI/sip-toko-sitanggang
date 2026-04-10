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
            'status' => ['nullable', 'in:normal,low,out,inactive'],
        ]);
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
        if (! empty($validated['status'])) {
            if ($validated['status'] === 'inactive') {
                $productsQuery->where('aktif', false);
            } elseif ($validated['status'] === 'out') {
                $productsQuery->where('aktif', true)->where('stok', '<=', 0);
            } elseif ($validated['status'] === 'low') {
                $productsQuery
                    ->where('aktif', true)
                    ->where('stok', '>', 0)
                    ->whereColumn('stok', '<=', 'stok_minimum');
            } elseif ($validated['status'] === 'normal') {
                $productsQuery
                    ->where('aktif', true)
                    ->whereColumn('stok', '>', 'stok_minimum');
            }
        }
        $products = $productsQuery
            ->orderBy('nama')
            ->get();
        $kategoris = Kategori::query()
            ->orderBy('nama')
            ->get();
        $filters = [
            'q' => $validated['q'] ?? null,
            'kategori_id' => $validated['kategori_id'] ?? null,
            'status' => $validated['status'] ?? null,
        ];
        // Data produk, kategori, dan filter lama dikirim agar tampilan stok tetap informatif.
        return view('stok.check', compact('products', 'kategoris', 'filters'));
    }
}
