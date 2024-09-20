<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentReceiveRequest;
use App\Models\PaymentReceive;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentReceiveController extends Controller
{

    public function index(Request $request)
    {
        try {

            $sale_id = $request->sale_id ?? null;

            $query = PaymentReceive::query();

            $query->with(['firm' => function ($q) {
                $q->select('id', 'firm_name as text');
            }, 'party' => function ($q) {
                $q->select('id', 'party_name as text');
            }, 'paymentType' => function ($q) {
                $q->select('id', 'text');
            }, 'expenseAccount' => function ($q) {
                $q->select('id', 'name as text');
            },]);

            $query->where([
                'sale_id' => $sale_id
            ]);

            $query->latest();

            $data = $query->get();

            $data = $data->map(function ($item) {
                $item->discount = '₹ ' . number_format($item->discount, 2);
                $item->amount = '₹ ' . number_format($item->amount, 2);
                $item->firm_name = $item->firm->text;
                $item->party_name = $item->party->text;
                $item->payment_type = $item->paymentType->text;
                $item->expense_account = $item->expenseAccount->text;
                unset($item->firm);
                unset($item->party);
                unset($item->paymentType);
                unset($item->expenseAccount);
                return $item;
            });

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function store(StorePaymentReceiveRequest $request)
    {
        try {
            $data = $request->all();

            // Store the data
            PaymentReceive::create($data);

            // Return success response
            return response()->json(['message' => 'Payment stored successfully!'], 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.', $e], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $paymentReceive = PaymentReceive::find($id);

            if ($paymentReceive) {
                $paymentReceive->delete();

                // Return success response
                return response()->json(['message' => 'Payment deleted successfully!'], 200);
            } else {
                // Return not found response
                return response()->json(['error' => 'Expense not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
