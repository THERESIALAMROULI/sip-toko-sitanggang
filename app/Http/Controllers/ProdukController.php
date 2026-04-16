<?php
namespace App\Http\Controllers;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', 'in:all,habis,menipis,normal'],
        ]);

        $search = trim((string) ($validated['q'] ?? ''));
        $selectedStatus = $validated['status'] ?? 'all';

        $productsQuery = Produk::query()
            ->with('kategori')
            ->orderBy('nama');

        if ($search !== '') {
            $productsQuery->where('nama', 'like', '%'.$search.'%');
        }

        if ($selectedStatus === 'habis') {
            $productsQuery->where('stok', '<=', 0);
        } elseif ($selectedStatus === 'menipis') {
            $productsQuery->where('stok', '>', 0)
                ->whereColumn('stok', '<=', 'stok_minimum');
        } elseif ($selectedStatus === 'normal') {
            $productsQuery->whereColumn('stok', '>', 'stok_minimum');
        }

        $productGroups = collect([
            [
                'key' => 'all',
                'label' => 'Semua',
                'badge' => 'badge-blue',
                'count' => Produk::query()->count(),
            ],
            [
                'key' => 'habis',
                'label' => 'Habis',
                'badge' => 'badge-red',
                'count' => Produk::query()->where('stok', '<=', 0)->count(),
            ],
            [
                'key' => 'menipis',
                'label' => 'Menipis',
                'badge' => 'badge-amber',
                'count' => Produk::query()
                    ->where('stok', '>', 0)
                    ->whereColumn('stok', '<=', 'stok_minimum')
                    ->count(),
            ],
            [
                'key' => 'normal',
                'label' => 'Normal',
                'badge' => 'badge-green',
                'count' => Produk::query()
                    ->whereColumn('stok', '>', 'stok_minimum')
                    ->count(),
            ],
        ]);

        $products = $productsQuery
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

        return view('produk.index', compact('products', 'productGroups', 'selectedStatus', 'search'));
    }
    public function create()
    {
        $kategoris = Kategori::query()
            ->orderBy('nama')
            ->get();
        return view('produk.create', compact('kategoris'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            'harga_beli' => ['required', 'integer', 'min:0'],
            'harga_jual' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        if ((int) $validated['stok'] <= (int) $validated['stok_minimum']) {
            throw ValidationException::withMessages([
                'stok' => 'Untuk produk baru, stok awal harus lebih besar dari stok minimum.',
            ]);
        }

        $validated['aktif'] = $request->boolean('aktif', true);
        Produk::create($validated);
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }
    public function edit(Produk $product)
    {
        $kategoris = Kategori::query()
            ->orderBy('nama')
            ->get();
        return view('produk.edit', compact('product', 'kategoris'));
    }
    public function update(Request $request, Produk $product)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            'harga_beli' => ['required', 'integer', 'min:0'],
            'harga_jual' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $hasSalesHistory = $product->transactionDetails()->exists();
        if (! $hasSalesHistory && (int) $validated['stok'] <= (int) $validated['stok_minimum']) {
            throw ValidationException::withMessages([
                'stok' => 'Produk yang belum pernah terjual harus memiliki stok di atas stok minimum.',
            ]);
        }

        $validated['aktif'] = $request->boolean('aktif', false);
        $product->update($validated);
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }
    public function destroy(Produk $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
