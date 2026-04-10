<?php
namespace App\Http\Controllers;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class KategoriController extends Controller
{
    // Menampilkan daftar kategori sebagai data acuan untuk pengelompokan produk.
    public function index()
    {
        $kategoris = Kategori::query()
            ->withCount('products')
            ->orderBy('nama')
            ->get();
        return view('kategoris.index', compact('kategoris'));
    }
    // Form ini dipakai admin untuk menambahkan kategori produk baru.
    public function create()
    {
        return view('kategoris.create');
    }
    // Penyimpanan kategori dijaga dengan validasi agar nama tidak duplikat.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:kategoris,nama'],
        ]);
        Kategori::create($validated);
        return redirect()->route('kategoris.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }
    // Edit kategori memungkinkan admin merapikan struktur data produk yang sudah ada.
    public function edit(Kategori $kategori)
    {
        return view('kategoris.edit', compact('kategori'));
    }
    // Saat update, nama kategori tetap harus unik kecuali untuk data yang sedang diedit.
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategoris', 'nama')->ignore($kategori->id),
            ],
        ]);
        $kategori->update($validated);
        return redirect()->route('kategoris.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }
    // Penghapusan kategori dibatasi agar tidak merusak relasi dengan produk yang masih aktif.
    public function destroy(Kategori $kategori)
    {
        if ($kategori->products()->exists()) {
            return redirect()->route('kategoris.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih dipakai produk.');
        }
        $kategori->delete();
        return redirect()->route('kategoris.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
