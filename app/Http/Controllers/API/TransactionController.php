<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class TransactionController extends BaseController
{

    public function index()
    {
        $date_start = request()->input('date_start');
        $date_end = request()->input('date_end');
        $perPage = request()->input('perPage', 10);

        $transactions = Transaction::with('TransactionDetail', 'TransactionPayment')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->latest()
            ->paginate($perPage);

        return $this->sendResponse(TransactionResource::collection($transactions), 'Transactions retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'details' => 'required',
            'details.*.product_id' => 'required',
            'details.*.quantity' => 'required|numeric',
            'details.*.price' => 'required|numeric',
            'paid' => 'required|numeric',
        ]);

        $validation = array_fill_keys(array_keys($request->all()), []);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $errors) {
                $validation[$key] = $errors;
            }
            return $this->sendErrorValidation($validation);
        }

        try {

            //hitung total
            $total = 0;
            foreach ($input['details'] as $detail) {
                $total += $detail['quantity'] * $detail['price'];
            }

            //buat transaksi
            $transaction = Transaction::create([
                'user_id' => Auth::user()->id,
                'total' => $total,
            ]);

            //buat detail transaksi
            foreach ($input['details'] as $detail) {
                $transaction->TransactionDetail()->create([
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                ]);

                //kurangi stok
                $product = Product::find($detail['product_id']);
                $product->decrement('stock', $detail['quantity']);
            }

            //pembayaran

            $transaction->TransactionPayment()->create([
                'paid' => $input['paid'],
                'change' => $input['paid'] - $total,
            ]);
            
            return $this->sendResponseValidation(new TransactionResource($transaction), 'Transaction created successfully.', $validation);
        } catch (\Throwable $th) {
            return $this->sendError('Transaction failed to create.');
        }
        

    }

    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (is_null($transaction)) {
            return $this->sendResponse([], 'Transaction not found.');
        }

        return $this->sendResponse(new TransactionResource($transaction), 'Transaction retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'details' => 'required',
            'details.*.product_id' => 'required',
            'details.*.quantity' => 'required|numeric',
            'details.*.price' => 'required|numeric',
            'paid' => 'required|numeric',
        ]);

        $validation = array_fill_keys(array_keys($request->all()), []);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $errors) {
                $validation[$key] = $errors;
            }
            return $this->sendErrorValidation($validation);
        }

        try {

            //hitung total
            $total = 0;
            foreach ($input['details'] as $detail) {
                $total += $detail['quantity'] * $detail['price'];
            }

            //buat transaksi
            $transaction = Transaction::find($id);
            $transaction->update([
                'user_id' => Auth::user()->id,
                'total' => $total,
            ]);

            //hapus detail transaksi
            $transaction->TransactionDetail()->delete();

            //buat detail transaksi
            foreach ($input['details'] as $detail) {
                $transaction->TransactionDetail()->create([
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                ]);

                //kurangi stok
                $product = Product::find($detail['product_id']);
                $product->decrement('stock', $detail['quantity']);
            }   

            //hapus pembayaran
            $transaction->TransactionPayment()->delete();

            //pembayaran
            $transaction->TransactionPayment()->create([
                'paid' => $input['paid'],
                'change' => $input['paid'] - $total,
            ]);

            return $this->sendResponseValidation(new TransactionResource($transaction), 'Transaction updated successfully.', $validation);
        } catch (\Throwable $th) {
            return $this->sendError('Transaction failed to update.');
        }
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return $this->sendResponse([], 'Transaction deleted successfully.');
    }

    
}
