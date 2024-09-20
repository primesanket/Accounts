<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseType;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
{
    public function listExpenseTypes(Request $request)
    {
        try {

            $query = ExpenseType::query();

            $data = $query->select('id', 'type')->get();

            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function storeExpenseTypes(Request $request)
    {

        try {
            $data = $request->all();
            $data['type'] = ucwords($data['type']);

            $expenseType = ExpenseType::updateOrCreate(
                ['type' => $data['type']],
                ['type' => $data['type']]
            );

            // Return success response
            return response()->json(['message' => 'Expense type stored successfully!', 'data' => $expenseType], 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
