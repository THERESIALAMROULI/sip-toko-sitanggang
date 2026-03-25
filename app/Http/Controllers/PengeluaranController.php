<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'expense_category_id' => ['nullable', 'integer', 'exists:expense_categories,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $expensesQuery = Pengeluaran::query()
            ->with(['category', 'user']);

        if (! empty($validated['expense_category_id'])) {
            $expensesQuery->where('expense_category_id', (int) $validated['expense_category_id']);
        }

        if (! empty($validated['start_date'])) {
            $expensesQuery->whereDate('tanggal', '>=', $validated['start_date']);
        }

        if (! empty($validated['end_date'])) {
            $expensesQuery->whereDate('tanggal', '<=', $validated['end_date']);
        }

        if (! empty($validated['q'])) {
            $search = trim($validated['q']);
            $expensesQuery->where(function ($query) use ($search) {
                $query->where('catatan', 'like', '%'.$search.'%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('nama', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        $expenses = $expensesQuery
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        $totalExpense = $expenses->sum('nominal');

        $expenseCategories = KategoriPengeluaran::query()
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        $filters = [
            'q' => $validated['q'] ?? null,
            'expense_category_id' => $validated['expense_category_id'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ];

        return view('expenses.index', compact(
            'expenses',
            'totalExpense',
            'expenseCategories',
            'filters'
        ));
    }

    public function create()
    {
        $expenseCategories = KategoriPengeluaran::query()
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('expenses.create', compact('expenseCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            'nominal' => ['required', 'integer', 'min:1'],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        Pengeluaran::create([
            'expense_category_id' => (int) $validated['expense_category_id'],
            'user_id' => auth()->id() ?? 1,
            'nominal' => (int) $validated['nominal'],
            'tanggal' => $validated['tanggal'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Biaya operasional berhasil ditambahkan.');
    }

    public function edit(Pengeluaran $expense)
    {
        $expenseCategories = KategoriPengeluaran::query()
            ->where('aktif', true)
            ->orWhere('id', $expense->expense_category_id)
            ->orderBy('nama')
            ->get();

        return view('expenses.edit', compact('expense', 'expenseCategories'));
    }

    public function update(Request $request, Pengeluaran $expense)
    {
        $validated = $request->validate([
            'expense_category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            'nominal' => ['required', 'integer', 'min:1'],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $expense->update([
            'expense_category_id' => (int) $validated['expense_category_id'],
            'nominal' => (int) $validated['nominal'],
            'tanggal' => $validated['tanggal'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Biaya operasional berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Biaya operasional berhasil dihapus.');
    }
}
