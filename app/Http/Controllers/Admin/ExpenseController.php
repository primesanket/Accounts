<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $query = Expense::query();

            $query->with(['expense_account' => function ($q) {
                $q->select('id', 'name');
            }, 'expense_type' => function ($q) {
                $q->select('id', 'type');
            }]);

            if ($request->has('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }

            $query->latest();

            $data = $query->paginate(10);

            // Format the data to include only the necessary columns
            $formattedData = $data->getCollection()->map(function ($expense) {
                return array_merge(
                    $expense->toArray(), // Include all fields from the Expense model
                    [
                        'amount' => '₹' . number_format($expense->amount, 2),
                        'expense_type' => $expense->expense_type ? $expense->expense_type->type : null,
                        'expense_account' => $expense->expense_account ? $expense->expense_account->name : null
                    ]
                );
            });

            // Create a new pagination data instance with the formatted data
            $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
                $formattedData,
                $data->total(),
                $data->perPage(),
                $data->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            // Return success response
            return response()->json($paginatedData, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExpenseRequest $request)
    {
        try {
            $data = $request->all();

            // Store the data
            Expense::create($data);

            // Return success response
            return response()->json(['message' => 'Expense stored successfully!'], 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $query = Expense::query();

            $query->with(['expense_account' => function ($q) {
                $q->select('id', 'name');
            }, 'expense_type' => function ($q) {
                $q->select('id', 'type');
            }]);

            $expense = $query->find($id);

            if ($expense) {

                if ($expense->expense_type) {
                    $expense->expense_type_id = $expense->expense_type; // Assign the ID
                }
                if ($expense->expense_account) {
                    $expense->expense_account_id = $expense->expense_account; // Assign the ID
                }

                // Return success response
                return response()->json(['message' => 'Expense fetched successfully!', 'data' => $expense], 201);
            } else {
                // Return not found response
                return response()->json(['error' => 'Expense not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreExpenseRequest $request, $id)
    {
        try {
            $expense = Expense::find($id);

            if ($expense) {
                $data = $request->all();
                $expense->update($data);

                // Return success response
                return response()->json(['message' => 'Expense updated successfully!', 'data' => $expense], 201);
            } else {
                // Return not found response
                return response()->json(['error' => 'Expense not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $expense = Expense::find($id);

            if ($expense) {
                $expense->delete();

                // Return success response
                return response()->json(['message' => 'Expense deleted successfully!'], 200);
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
