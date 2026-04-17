<?php
namespace App\Http\Controllers;
use App\Models\Pelanggan;
use App\Models\Piutang;
use Illuminate\Http\Request;
class PiutangController extends Controller
{
    // Daftar piutang menampilkan tagihan pelanggan yang berasal dari transaksi kredit.
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'group' => ['nullable', 'in:all,paid,overdue'],
            'status' => ['nullable', 'in:unpaid,paid'],
            'customer_id' => ['nullable', 'exists:pelanggans,id'],
            'overdue_only' => ['nullable', 'in:1'],
        ]);
        $selectedReceivableGroup = $validated['group'] ?? null;
        if ($selectedReceivableGroup === null) {
            $selectedReceivableGroup = ! empty($validated['overdue_only'])
                ? 'overdue'
                : (($validated['status'] ?? null) === 'paid' ? 'paid' : 'all');
        }

        $receivablesQuery = Piutang::query()
            ->with('transaction.customer');
        // Filter membantu kasir memantau piutang berdasarkan status, pelanggan, dan jatuh tempo.
        if ($selectedReceivableGroup === 'paid') {
            $receivablesQuery->where('status', 'lunas');
        } elseif ($selectedReceivableGroup === 'overdue') {
            $receivablesQuery
                ->where('status', 'belum')
                ->whereDate('jatuh_tempo', '<', now()->toDateString());
        } elseif (! empty($validated['status']) && $validated['status'] === 'unpaid') {
            $receivablesQuery->where('status', 'belum');
        }
        if (! empty($validated['customer_id'])) {
            $customerId = (int) $validated['customer_id'];
            $receivablesQuery->whereHas('transaction', function ($query) use ($customerId) {
                $query->where('pelanggan_id', $customerId);
            });
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
        $receivableGroups = collect([
            [
                'key' => 'all',
                'label' => 'Semua',
                'count' => Piutang::query()->count(),
            ],
            [
                'key' => 'paid',
                'label' => 'Lunas',
                'count' => Piutang::query()->where('status', 'lunas')->count(),
            ],
            [
                'key' => 'overdue',
                'label' => 'Jatuh Tempo',
                'count' => Piutang::query()
                    ->where('status', 'belum')
                    ->whereDate('jatuh_tempo', '<', now()->toDateString())
                    ->count(),
            ],
        ]);
        // Ringkasan ini dipakai untuk menunjukkan total piutang aktif, jatuh tempo, dan jumlah yang sudah lunas.
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
            'group' => $selectedReceivableGroup,
            'status' => $validated['status'] ?? null,
            'customer_id' => $validated['customer_id'] ?? null,
            'overdue_only' => $validated['overdue_only'] ?? null,
        ];
        return view('piutang.index', compact(
            'receivables',
            'customers',
            'receivableGroups',
            'selectedReceivableGroup',
            'summaryUnpaidAmount',
            'summaryOverdueAmount',
            'summaryPaidCount',
            'filters'
        ));
    }
    // Edit piutang dipakai untuk membuka data tagihan yang akan diubah status pembayarannya.
    public function edit(Piutang $receivable)
    {
        $receivable->load('transaction.customer');
        return view('piutang.edit', compact('receivable'));
    }
    // Saat pelunasan dilakukan, sistem mencatat status, waktu pelunasan, dan pengguna yang memprosesnya.
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
}
