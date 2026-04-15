<?php
namespace App\Http\Controllers;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class ProdukController extends Controller
{
    public function index()
    {
        $products = Produk::query()
            ->with('kategori')
            ->orderBy('nama')
            ->get();
        return view('produk.index', compact('products'));
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
