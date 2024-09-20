<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseAccount;
use Illuminate\Http\Request;

class ExpenseAccountController extends Controller
{
    public function listExpenseAccounts(Request $request)
    {
        try {

            $query = ExpenseAccount::query();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $data = $query->select('id', 'name')->get();


            // Return success response
            return response()->json($data, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
