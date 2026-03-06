<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'payment_type' => ['nullable', 'in:cash,transfer,qris,credit'],
        ]);

        $query = Transaction::with('customer');

        if (! empty($validated['start_date'])) {
            $query->where('transaction_date', '>=', $validated['start_date'].' 00:00:00');
        }

        if (! empty($validated['end_date'])) {
            $query->where('transaction_date', '<=', $validated['end_date'].' 23:59:59');
        }

        if (! empty($validated['payment_type'])) {
            $query->where('payment_type', $validated['payment_type']);
        }

        $transactions = $query
            ->orderByDesc('transaction_date')
            ->get();

        $totalSales = $transactions->sum('total');

        $chartData = $transactions
            ->groupBy(fn ($transaction) => $transaction->transaction_date->format('Y-m'))
            ->map(fn ($items) => $items->sum('total'))
            ->sortKeys();

        $chartLabels = $chartData->keys()->values();
        $chartTotals = $chartData->values();

        return view('reports.sales', compact(
            'transactions',
            'totalSales',
            'chartLabels',
            'chartTotals'
        ));
    }
}
