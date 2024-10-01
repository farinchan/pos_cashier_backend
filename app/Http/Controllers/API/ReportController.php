<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Spending;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    public function all(Request $request)
    {
        $date_start = $request->input('date_start', date('Y-m-d 00:00:00'));
        $date_end = $request->input('date_end', date('Y-m-d 23:59:59'));

        $spendings = Spending::whereBetween('created_at', [$date_start, $date_end])
            ->latest();
        $transactions = Transaction::whereBetween('created_at', [$date_start, $date_end])
            ->latest();

        $data = [

            'spending_count' => $spendings->count(),
            'spending_total' => $spendings->sum('price'),
            'spendings' => $spendings->get(),
            'transaction_total' => $transactions->sum('total'),
            'transaction_count' => $transactions->count(),
            'transactions' => $transactions->get(),
            'profit' => $transactions->sum('total') - $spendings->sum('price'),
        ];

        return $this->sendResponse($data, 'Report retrieved successfully.');
    }
}
