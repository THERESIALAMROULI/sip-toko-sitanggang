<?php
namespace App\Http\Controllers;
use App\Models\Pemasok;
use Illuminate\Http\Request;
class PemasokController extends Controller
{
    public function index()
    {
        $suppliers = Pemasok::query()
            ->withCount('stokHistories')
            ->orderBy('nama')
            ->get();
        return view('pemasok.index', compact('suppliers'));
    }
    public function create()
    {
        return view('pemasok.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'aktif' => ['nullable', 'boolean'],
        ]);
        $validated['aktif'] = $request->boolean('aktif', true);
        Pemasok::create($validated);
        return redirect()->route('suppliers.index')
            ->with('success', 'Pemasok berhasil ditambahkan.');
    }
    public function edit(Pemasok $supplier)
    {
        return view('pemasok.edit', compact('supplier'));
    }
    public function update(Request $request, Pemasok $supplier)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'aktif' => ['nullable', 'boolean'],
        ]);
        $validated['aktif'] = $request->boolean('aktif', false);
        $supplier->update($validated);
        return redirect()->route('suppliers.index')
            ->with('success', 'Pemasok berhasil diperbarui.');
    }
    public function destroy(Pemasok $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')
            ->with('success', 'Pemasok berhasil dihapus.');
    }
}
