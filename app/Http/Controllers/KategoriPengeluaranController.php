<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriPengeluaranController extends Controller
{
    public function index()
    {
        $expenseCategories = KategoriPengeluaran::query()
            ->withCount('expenses')
            ->orderBy('nama')
            ->get();

        return view('expense_categories.index', compact('expenseCategories'));
    }

    public function create()
    {
        return view('expense_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:expense_categories,nama'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $validated['aktif'] = $request->boolean('aktif', true);

        KategoriPengeluaran::create($validated);

        return redirect()->route('expense_categories.index')
            ->with('success', 'Kategori biaya berhasil ditambahkan.');
    }

    public function edit(KategoriPengeluaran $expenseCategory)
    {
        return view('expense_categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, KategoriPengeluaran $expenseCategory)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('expense_categories', 'nama')->ignore($expenseCategory->id),
            ],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $validated['aktif'] = $request->boolean('aktif', false);

        $expenseCategory->update($validated);

        return redirect()->route('expense_categories.index')
            ->with('success', 'Kategori biaya berhasil diperbarui.');
    }

    public function destroy(KategoriPengeluaran $expenseCategory)
    {
        if ($expenseCategory->expenses()->exists()) {
            return redirect()->route('expense_categories.index')
                ->with('error', 'Kategori biaya tidak bisa dihapus karena sudah dipakai di data biaya.');
        }

        $expenseCategory->delete();

        return redirect()->route('expense_categories.index')
            ->with('success', 'Kategori biaya berhasil dihapus.');
    }
}
