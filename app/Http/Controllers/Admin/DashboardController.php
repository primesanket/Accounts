<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\PaymentReceive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getGeneralReport(Request $request)
    {
        $currentYear = date('Y');

        $report = [
            'total' => $this->getTotalReport(),
            'monthly_data' => $this->getMonthlyReport($currentYear),
            'payment_recieved_data' => $this->getPaymentRecievedByReciever($request),
            'outstanding_data' => $this->outstandingReport(),
        ];

        return response()->json($report);
    }

    public function getPaymentRecievedByReciever(Request $request)
    {
        try {

            $query = PaymentReceive::query()
                ->select('expense_account_id', DB::raw('SUM(amount) as total_amount'))
                ->when($request && $request->has('date'), function ($query) use ($request) {
                    $query->whereMonth('date', $request->date['month'])
                        ->whereYear('date', $request->date['year']);
                })
                ->groupBy('expense_account_id');

            // Fetch the results with necessary data only
            $results = $query->get();

            // Prepare data for Chart.js
            $labels = $results->map(function ($paymentReceive) {
                return $paymentReceive->expenseAccount->name ?? 'Unknown'; // Handle missing relation if needed
            });
            $amounts = $results->pluck('total_amount');

            return [
                'labels' => $labels,
                'data' => $amounts,
            ];
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.', $e], 500);
        }
    }

    private function getTotalReport()
    {
        return [
            'expenses' => Expense::sum('amount'),
            'incomes' => PaymentReceive::sum('amount'),
            'profit' => PaymentReceive::sum('amount') - Expense::sum('amount'),
        ];
    }

    private function getMonthlyReport($year)
    {
        // Month names for the year
        $monthNames = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];

        // Initialize arrays with zeroes for each month
        $monthlyExpensesArray = array_fill_keys(array_keys($monthNames), 0);
        $monthlyIncomesArray = array_fill_keys(array_keys($monthNames), 0);

        // Get monthly data in one query each
        $monthlyExpenses = Expense::selectRaw("MONTH(expense_date) AS month, SUM(amount) AS total_expenses")
            ->whereYear('expense_date', $year)
            ->groupBy('month')
            ->pluck('total_expenses', 'month')
            ->toArray();

        $monthlyIncomes = PaymentReceive::selectRaw("MONTH(date) AS month, SUM(amount) AS total_incomes")
            ->whereYear('date', $year)
            ->groupBy('month')
            ->pluck('total_incomes', 'month')
            ->toArray();

        // Populate the months with actual data
        foreach ($monthlyExpenses as $month => $total) {
            $monthlyExpensesArray[$month] = $total;
        }

        foreach ($monthlyIncomes as $month => $total) {
            $monthlyIncomesArray[$month] = $total;
        }

        return [
            'expenses' => array_values($monthlyExpensesArray),
            'incomes' => array_values($monthlyIncomesArray),
        ];
    }

    private function outstandingReport()
    {
        $currentDate = Carbon::now();
        $request = new Request();
        $request->merge(['date' => ['month' => $currentDate->month, 'year' => $currentDate->year]]);
        $response = (new ReportController)->balanceSheetReport($request);
        $mehulFrofit = 0;
        $bhavinFrofit = 0;
        $mehulCarryForwordFrofit = 0;
        $bhavinCarryForwordFrofit = 0;
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

            if ($response['carry_forword_profit']) {
                $mehulCarryForwordFrofit = $response['carry_forword_profit']['mehul_profit'];
                $bhavinCarryForwordFrofit = $response['carry_forword_profit']['bhavin_profit'];
            }
        }

        return [
            "mehul_profit" => $mehulFrofit + $mehulCarryForwordFrofit,
            "bhavin_profit" => $bhavinFrofit + $bhavinCarryForwordFrofit
        ];
    }
}
