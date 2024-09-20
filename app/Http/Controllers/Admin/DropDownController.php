<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillType;
use App\Models\Currency;
use App\Models\ExpenseAccount;
use App\Models\ExpenseType;
use App\Models\Firm;
use App\Models\Party;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DropDownController extends Controller
{

    public function expense_types(Request $request)
    {
        try {

            $query = ExpenseType::query();

            $data = $query->select('id', 'type as text')->get();

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function expense_accounts(Request $request)
    {
        try {

            $query = ExpenseAccount::query();
            $data = $query->select('id', 'name')->get();

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function firms(Request $request)
    {
        try {

            $query = Firm::query();

            $data = $query->select('id', 'firm_name')->get();

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function bills(Request $request)
    {
        try {

            $party_id = $request->party_id ?? null;

            $query = Sale::query();
            $query->with(['payments' => function ($q) {
                $q->select('sale_id', DB::raw('SUM(amount) as received_amount'), DB::raw('SUM(discount) as total_discount'))
                    ->groupBy('sale_id');
            }]);
            $query->where([
                'party_id' => $party_id
            ]);
            $data = $query->select('id', 'bill_no', 'grand_total', 'sale_date')->get();

            // Transform the data to include 'received_amount' directly in the main array
            $data = $data->map(function ($sale) {

                $received_amount = $sale->payments->isNotEmpty()
                    ? $sale->payments->first()->received_amount
                    : 0;

                $total_discount = $sale->payments->isNotEmpty()
                    ? $sale->payments->first()->total_discount
                    : 0;

                $sale->received_amount = $received_amount + $total_discount;
                unset($sale->payments);
                return $sale;
            });

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function bill_types(Request $request)
    {
        try {

            $query = BillType::query();

            $data = $query->select('id', 'alias', 'text')->get();

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function currencies(Request $request)
    {
        try {

            $query = Currency::query();

            $data = $query->select('id', 'text')->orderBy('id')->get();

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function payment_types(Request $request)
    {
        try {

            $query = PaymentType::query();

            $data = $query->select('id', 'text')->orderBy('id')->get();

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function parties(Request $request)
    {
        try {

            $query = Party::query();

            $data = $query->select('id', 'party_name')->orderBy('party_name')->get();

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function products(Request $request)
    {
        try {

            $query = Product::query();

            $data = $query->select('id', 'product_name', 'gst')->orderBy('product_name')->get();

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
