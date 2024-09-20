<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseAccount;
use App\Models\PaymentReceive;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{

    public $opening_balances;

    public function __construct()
    {
        $this->opening_balances = Setting::select('mehul_opening_balance as mehul', 'bhavin_opening_balance as bhavin')->first();
    }

    public function generateReportPDF(Request $request, $type)
    {
        try {
            $result = [];
            if ($type === 'expense') {
                $result = $this->expenseReport($request);
            } elseif ($type === 'payment_received') {
                $result = $this->paymentReceivedReport($request);
            } elseif ($type === 'balance_sheet') {
                $result = $this->balanceSheetReport($request);
            }

            // return $result;

            if (!$result || isset($result['grouped_data']) && sizeof($result['grouped_data']) == 0) {
                return response()->json(['error' => 'Data not found.'], 404);
            }

            $pdf = PDF::loadView('reports.' . $type, $result)
                ->setPaper('A4', 'portrait');

            return $request->method() === 'GET'
                ? $pdf->stream()
                : $pdf->download('invoice.pdf');
        } catch (\Exception $e) {
            // Log the exception details if necessary
            Log::error('Error generating PDF report: ' . $e->getMessage());

            return response()->json(['error' => 'An unexpected error occurred.', $e], 500);
        }
    }

    public function expenseReport(Request $request)
    {
        $query = Expense::query()
            ->with([
                'expense_account:id,name',
                'expense_type:id,type'
            ]);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('expense_date', [$request->input('from_date'), $request->input('to_date')]);
        }

        if ($request->filled('expense_account_id')) {
            $query->where('expense_account_id', $request->input('expense_account_id'));
        }

        if ($request->filled('expense_type_id')) {
            $query->where('expense_type_id', $request->input('expense_type_id'));
        }

        $data = $query->select('expense_date', 'amount', 'expense_type_id', 'expense_account_id', 'description as remark')
            ->latest()
            ->get()
            ->groupBy('expense_account_id');

        $data = $data->map(function ($group) {
            $firstItem = $group->first();
            return [
                'expense_account' => $firstItem->expense_account->name ?? null,
                'total_expenses' => $group->sum('amount'),
                'expenses' => $group->map(function ($item) {
                    $itemArray = $item->toArray();
                    unset($itemArray['expense_type_id'], $itemArray['expense_account_id'], $itemArray['expense_account']);

                    if (isset($itemArray['expense_date'])) {
                        $itemArray['expense_date'] = Carbon::parse($itemArray['expense_date'])->format('d/m/Y');
                    }

                    return array_merge(
                        $itemArray,
                        ['expense_type' => $item->expense_type->type ?? null]
                    );
                })
            ];
        });

        $overallTotalExpenses = $data->sum('total_expenses');

        return [
            'title' => 'Expense Report',
            'overall_total_expenses' => $overallTotalExpenses,
            'grouped_data' => $data->values()
        ];
    }

    public function paymentReceivedReport(Request $request)
    {
        $query = PaymentReceive::query()
            ->with([
                'expenseAccount:id,name',
                'party:id,party_name',
                'sale:id,bill_no',
                'paymentType:id,text'
            ]);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->input('from_date'), $request->input('to_date')]);
        }

        if ($request->filled('expense_account_id')) {
            $query->where('expense_account_id', $request->input('expense_account_id'));
        }

        if ($request->filled('party_id')) {
            $query->where('party_id', $request->input('party_id'));
        }

        if ($request->filled('sale_id')) {
            $query->where('sale_id', $request->input('sale_id'));
        }

        $data = $query->select('party_id', 'sale_id', 'amount', 'payment_type_id', 'expense_account_id', 'remark', 'date')
            ->latest()
            ->get()
            ->groupBy('expense_account_id');

        $data = $data->map(function ($group) {
            $firstItem = $group->first();
            return [
                'expense_account' => $firstItem->expenseAccount->name ?? null,
                'total_amount' => $group->sum('amount'),
                'payments' => $group->map(function ($item) {
                    $itemArray = $item->toArray();
                    unset($itemArray['party_id'], $itemArray['sale_id'], $itemArray['expense_account_id'], $itemArray['payment_type_id'], $itemArray['expense_account'], $itemArray['party'], $itemArray['sale'], $itemArray['paymentType']);

                    if (isset($itemArray['date'])) {
                        $itemArray['date'] = Carbon::parse($itemArray['date'])->format('d/m/Y');
                    }

                    return array_merge(
                        $itemArray,
                        ['party_name' => $item->party->party_name ?? null, 'bill_no' => $item->sale->bill_no ?? null, 'payment_type' => $item->paymentType->text ?? null]
                    );
                })
            ];
        });

        $overallTotal = $data->sum('total_amount');

        return [
            'title' => 'Payment Received Report',
            'overall_total' => $overallTotal,
            'grouped_data' => $data->values()
        ];
    }

    public function balanceSheetReport(Request $request, $isCarryForword = false)
    {
        $month = $request->has('date.month');
        $year = $request->has('date.year');

        $expenseAccounts = ExpenseAccount::with([
            'expense' => function ($query) use ($request, $month, $year) {
                if ($month && $year) {
                    $query->whereMonth('expense_date', $request->input('date.month'))
                        ->whereYear('expense_date', $request->input('date.year'));
                }
            },
            'incomes' => function ($query) use ($request, $month, $year) {
                if ($month && $year) {
                    $query->whereMonth('date', $request->input('date.month'))
                        ->whereYear('date', $request->input('date.year'));
                }
            }
        ])->get();

        // Filter out the expense accounts with empty expenses or incomes
        $filteredExpenseAccounts = $expenseAccounts->filter(function ($expenseAccount) {
            return $expenseAccount->expense->isNotEmpty() || $expenseAccount->incomes->isNotEmpty();
        });

        $expenseAccounts = $filteredExpenseAccounts->values();

        $expenseAccountsForAdvance = ExpenseAccount::with([
            'expense' => function ($query) use ($request, $month, $year) {
                if ($month && $year) {
                    $query->whereMonth('expense_date', $request->input('date.month'))
                        ->whereYear('expense_date', $request->input('date.year'))
                        ->whereIn('expense_type_id', [1, 4]);
                }
            },
            'expense.expense_type'
        ])->get();


        $filteredAccountsForAdvance = $expenseAccountsForAdvance->filter(function ($expenseAccount) {
            return $expenseAccount->expense->isNotEmpty();
        });

        // Re-index the filtered collection
        $expenseAccountsForAdvance = $filteredAccountsForAdvance->values();

        // Initialize totals
        $totalExpenses = 0;
        $totalIncomes = 0;
        $totalAdvances = 0;

        // Create a map of advances for lookup
        $advanceMap = $expenseAccountsForAdvance->flatMap(function ($account) {
            return $account->expense->map(function ($expense) use ($account) {
                return [
                    'name' => $account->name,
                    'amount' => $expense->amount,
                ];
            });
        })->groupBy('name')->map(function ($items) {
            return $items->sum('amount');
        })->toArray();

        // Calculate totals for expenses
        $expenses = $expenseAccounts->map(function ($account) use (&$totalExpenses, $advanceMap) {
            $totalAmount = $account->expense->sum('amount');
            $totalAmount -= $advanceMap[$account->name] ?? 0; // Deduct advances from expenses
            $totalExpenses += $totalAmount; // Accumulate overall total
            return [
                'name' => $account->name,
                'total_amount' => $totalAmount,
            ];
        })->filter(function ($expense) {
            return $expense['total_amount'] > 0; // Keep only expenses with a total_amount > 0
        })->sortBy('name')->values();

        // Calculate totals for incomes
        $incomes = $expenseAccounts->map(function ($account) use (&$totalIncomes) {
            $totalAmount = $account->incomes->sum('amount');
            $totalIncomes += $totalAmount; // Accumulate overall total
            return [
                'name' => $account->name,
                'total_amount' => $totalAmount,
            ];
        })->filter(function ($income) {
            return $income['total_amount'] > 0; // Keep only incomes with a total_amount > 0
        })->sortBy('name')->values();

        // Calculate totals for advances and group by expense type
        $advances = $expenseAccountsForAdvance->flatMap(function ($account) use (&$totalAdvances) {
            return $account->expense->map(function ($expense) use ($account, &$totalAdvances) {
                $totalAdvances += $expense->amount; // Accumulate overall total
                return [
                    'name' => $account->name,
                    'expense_type' => $expense->expense_type->type,
                    'amount' => $expense->amount,
                ];
            });
        })->groupBy('expense_type')->map(function ($group, $expenseType) {
            return [
                'salary_name' => $expenseType,
                'total_amount' => $group->sum('amount'),
                'details' => $group->map(function ($item) {
                    return [
                        'name' => $item['name'],
                        'amount' => $item['amount'],
                    ];
                })->sortBy('name')->values()->toArray(),
            ];
        })->filter(function ($advance) {
            return $advance['total_amount'] > 0; // Keep only advances with a total_amount > 0
        })->values()->toArray();


        // Convert numeric month to its name
        $monthName = date('F', mktime(0, 0, 0, $request->date['month'], 1));

        // Prepare the report
        return [
            'title' => $request->date['month'] ? "Balance Sheet - $monthName, {$request->date['year']}" : "Balance Sheet",
            'expenses' => [
                'data' => $expenses,
                'overall_total' => $totalExpenses,
            ],
            'incomes' => [
                'data' => $incomes,
                'overall_total' => $totalIncomes,
            ],
            'advances' => [
                'data' => $advances,
                'overall_total' => $totalAdvances,
            ],
            'opening_balances' => $this->opening_balances,
            'carry_forword_profit' => !$isCarryForword ? $this->getCarryForwordProfit($request->date) : [
                "mehul_profit" => 0,
                "bhavin_profit" => 0
            ]
        ];
    }

    public function getCarryForwordProfit($date = null)
    {
        if ($date) {
            $date = Carbon::parse($date['year'] . '-' . $date['month'] . '-01')->format('Y-m-d');
        }

        $uniqueMonths = DB::table('expenses')
            ->selectRaw('YEAR(expense_date) as year, MONTH(expense_date) as month')
            ->distinct()
            ->whereRaw("DATE(CONCAT(year(expense_date), '-', LPAD(month(expense_date), 2, '0'), '-01')) < ?", [$date])
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $mehulFrofit = 0;
        $bhavinFrofit = 0;

        foreach ($uniqueMonths as $key => $date) {
            $request = new Request();
            $request->merge(['date' => ['month' => $date->month, 'year' => $date->year]]);

            $response = Self::balanceSheetReport($request, true);

            if ($response) {

                $overall_total_profits = $response['incomes']['overall_total'] - $response['expenses']['overall_total'];
                $overall_individual_profit = $overall_total_profits / 2;

                $mehulExpense = collect($response['expenses']['data'])->where('name', 'Mehul')->sum('total_amount');
                $mehulIncomes = collect($response['incomes']['data'])->whereIn('name', ['Mehul', 'Mehul HUF'])->sum('total_amount');
                $mehulSalary = collect($response['advances']['data'])->where('salary_name', 'Mehul Salary')->sum('total_amount');
                $mehulFrofit += ($overall_individual_profit + $mehulExpense) - ($mehulIncomes + $mehulSalary);

                $bhavinExpense = collect($response['expenses']['data'])->where('name', 'Bhavin')->sum('total_amount');
                $bhavinIncomes = collect($response['incomes']['data'])->whereIn('name', ['Bhavin'])->sum('total_amount');
                $bhavinSalary = collect($response['advances']['data'])->where('salary_name', 'Bhavin Salary')->sum('total_amount');
                $bhavinFrofit += ($overall_individual_profit + $bhavinExpense) - ($bhavinIncomes + $bhavinSalary);
            }
        }

        return [
            "mehul_profit" => $mehulFrofit + ($this->opening_balances ? $this->opening_balances->mehul : 0),
            "bhavin_profit" => $bhavinFrofit + ($this->opening_balances ? $this->opening_balances->bhavin : 0)
        ];
    }
}
