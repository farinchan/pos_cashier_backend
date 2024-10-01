<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Spending;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    public function index(Request $request)
    {
        $date_start = $request->input('date_start', date('Y-m-d 00:00:00'));
        $date_end = $request->input('date_end', date('Y-m-d 23:59:59'));

        $spendings = Spending::whereBetween('created_at', [$date_start, $date_end])
            ->latest();
        $transactions = Transaction::with('TransactionDetail', 'TransactionPayment')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->latest();

        $data = [

            'spending_count' => $spendings->count(),
            'spending_total' => $spendings->sum('price'),
            'spendings' => $spendings,
            'transaction_total' => $transactions->sum('total'),
            'transaction_count' => $transactions->count(),
            'transactions' => $transactions,
            'profit' => $transactions->sum('total') - $spendings->sum('price'),
            'most_sold_product' => $transactions->with('TransactionDetail')->get()->map(function ($transaction) {
                return $transaction->TransactionDetail->map(function ($detail) {
                    return [
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name,
                        'quantity' => $detail->quantity,
                    ];
                });
            })->flatten()->groupBy('product_id')->map(function ($item) {
                return [
                    'product_id' => $item->first()['product_id'],
                    'product_name' => $item->first()['product_name'],
                    'quantity' => $item->sum('quantity'),
                ];
            })->sortByDesc('quantity')->first(),
        ];

        return $this->sendResponse($data, 'Report retrieved successfully.');
    }
}
