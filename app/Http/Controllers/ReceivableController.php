<?php

namespace App\Http\Controllers;

use App\Models\Receivable;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    public function index()
    {
        $receivables = Receivable::with('transaction.customer')
            ->orderBy('status')
            ->orderBy('due_date')
            ->get();

        return view('receivables.index', compact('receivables'));
    }

    public function create()
    {
        return redirect()->route('receivables.index')
            ->with('error', 'Input manual piutang dinonaktifkan. Piutang dibuat otomatis dari transaksi kredit.');
    }

    public function store(Request $request)
    {
        return redirect()->route('receivables.index')
            ->with('error', 'Input manual piutang dinonaktifkan. Piutang dibuat otomatis dari transaksi kredit.');
    }

    public function edit(Receivable $receivable)
    {
        return view('receivables.edit', compact('receivable'));
    }

    public function update(Request $request, Receivable $receivable)
    {
        $validated = $request->validate([
            'status' => 'required|in:unpaid,paid',
        ]);

        $receivable->update($validated);

        return redirect()->route('receivables.index')
            ->with('success', 'Status piutang berhasil diperbarui.');
    }

    public function destroy(Receivable $receivable)
    {
        $receivable->delete();

        return redirect()->route('receivables.index')
            ->with('success', 'Piutang berhasil dihapus');
    }
}
