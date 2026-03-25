<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Piutang;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:unpaid,paid'],
            'customer_id' => ['nullable', 'exists:pelanggans,id'],
            'overdue_only' => ['nullable', 'in:1'],
        ]);

        $receivablesQuery = Piutang::query()
            ->with('transaction.customer');

        if (! empty($validated['status'])) {
            $receivablesQuery->where('status', $validated['status'] === 'paid' ? 'lunas' : 'belum');
        }

        if (! empty($validated['customer_id'])) {
            $customerId = (int) $validated['customer_id'];
            $receivablesQuery->whereHas('transaction', function ($query) use ($customerId) {
                $query->where('pelanggan_id', $customerId);
            });
        }

        if (! empty($validated['overdue_only'])) {
            $receivablesQuery
                ->where('status', 'belum')
                ->whereDate('jatuh_tempo', '<', now()->toDateString());
        }

        if (! empty($validated['q'])) {
            $search = trim($validated['q']);
            $receivablesQuery->where(function ($query) use ($search) {
                $query->whereHas('transaction.customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('nama', 'like', '%'.$search.'%');
                });

                if (is_numeric($search)) {
                    $query->orWhere('penjualan_id', (int) $search);
                }
            });
        }

        $receivables = $receivablesQuery
            ->orderBy('status')
            ->orderByDesc('created_at')
            ->get();

        $customers = Pelanggan::query()
            ->orderBy('nama')
            ->get();

        $summaryUnpaidAmount = $receivables
            ->where('status', 'unpaid')
            ->sum('amount');

        $summaryOverdueAmount = $receivables
            ->filter(fn (Piutang $receivable) => $receivable->status === 'unpaid'
                && $receivable->due_date
                && $receivable->due_date->lt(now()))
            ->sum('amount');

        $summaryPaidCount = $receivables
            ->where('status', 'paid')
            ->count();

        $filters = [
            'q' => $validated['q'] ?? null,
            'status' => $validated['status'] ?? null,
            'customer_id' => $validated['customer_id'] ?? null,
            'overdue_only' => $validated['overdue_only'] ?? null,
        ];

        return view('receivables.index', compact(
            'receivables',
            'customers',
            'summaryUnpaidAmount',
            'summaryOverdueAmount',
            'summaryPaidCount',
            'filters'
        ));
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

    public function edit(Piutang $receivable)
    {
        $receivable->load('transaction.customer');

        return view('receivables.edit', compact('receivable'));
    }

    public function update(Request $request, Piutang $receivable)
    {
        $validated = $request->validate([
            'status' => 'required|in:unpaid,paid',
        ]);

        $payload = [
            'status' => $validated['status'],
            'paid_at' => $validated['status'] === 'paid' ? now() : null,
            'paid_by' => $validated['status'] === 'paid' ? auth()->id() : null,
        ];

        $receivable->update($payload);

        return redirect()->route('receivables.index')
            ->with('success', 'Status piutang berhasil diperbarui.');
    }

    public function destroy(Piutang $receivable)
    {
        $receivable->delete();

        return redirect()->route('receivables.index')
            ->with('success', 'Piutang berhasil dihapus');
    }
}
