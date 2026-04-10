<?php
namespace App\Http\Controllers;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
class PelangganController extends Controller
{
    public function index()
    {
        $customers = Pelanggan::query()->orderBy('nama')->get();
        return view('pelanggan.index', compact('customers'));
    }
    public function create()
    {
        return view('pelanggan.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:25',
            'address' => 'nullable|string|max:1000',
        ]);
        Pelanggan::create($validated);
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }
    public function edit(Pelanggan $customer)
    {
        return view('pelanggan.edit', compact('customer'));
    }
    public function update(Request $request, Pelanggan $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:25',
            'address' => 'nullable|string|max:1000',
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil diperbarui');
    }
    public function destroy(Pelanggan $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }
}
