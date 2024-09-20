<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{$title}}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @page {
            margin: 10px;
        }

        body {
            font-family: 'Arial, sans-serif';
            font-size: 10px;
        }

        .container-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
            /* Adds a gap between the tables */
        }

        .container-table td {
            vertical-align: top;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px 10px;
            /* 4px top and bottom, 10px left and right */
            text-align: center;
            font-size: 12px;
        }

        .table th,
        .total-row td {
            background-color: #B0C4DE;
            font-size: 12px;
        }

        .total-row {
            font-weight: bold;
            font-size: 12px;
        }

        .header {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .main-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
            padding: 10px;
            margin-left: 15px;
            margin-right: 15px;
            background-color: #f0f8ff;
            border: 1px solid #000;
            border-radius: 5px;
        }

        .full-width-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .full-width-table th,
        .full-width-table td {
            border: 1px solid #000;
            padding: 4px 10px;
            text-align: center;
            font-size: 12px;
        }

        .full-width-table th {
            background-color: #B0C4DE;
        }
    </style>
</head>

<body>

    <!-- Main Title -->
    <div class="main-title">{{ $title }}</div>

    <table class="container-table">
        <tr>
            <td style="width: 33%; padding-left: 5px;padding-right: 5px;">
                <!-- Incomes Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="2">
                                <h2 style="margin:5px 0;margin-top:3px;padding:0;" class="header">Incomes</h2>
                            </th>
                        </tr>
                        <tr>
                            <th style="text-align: left;">Name</th>
                            <th style="text-align: right;width: 80px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic Incomes Data -->
                        @foreach ($incomes['data'] as $income)
                        <tr>
                            <td style="text-align: left;">{{ $income['name'] }}</td>
                            <td style="text-align: right;">{{ number_format($income['total_amount'], 2, '.', ',') }}</td>
                        </tr>
                        @endforeach
                        <!-- Empty Rows if less than 10 -->
                        @for ($i = count($incomes['data']); $i < 8; $i++)
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
        </tr>
        @endfor
        <!-- Group Total -->
        <tr class="total-row">
            <td style="text-align: right;">Total</td>
            <td style="text-align: right;">{{ number_format($incomes['overall_total'], 2, '.', ',') }}</td>
        </tr>
        </tbody>
    </table>
    </td>
    <td style="width: 33%; padding-right: 5px;">
        <!-- Expenses Table -->
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">
                        <h2 style="margin:5px 0;margin-top:3px;padding:0;" class="header">Expenses</h2>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left;">Name</th>
                    <th style="text-align: right;width: 80px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic Expenses Data -->
                @foreach ($expenses['data'] as $expense)
                <tr>
                    <td style="text-align: left;">{{ $expense['name'] }}</td>
                    <td style="text-align: right;">{{ number_format($expense['total_amount'], 2, '.', ',') }}</td>
                </tr>
                @endforeach
                <!-- Empty Rows if less than 10 -->
                @for ($i = count($expenses['data']); $i < 8; $i++)
                    <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                    @endfor
                    <!-- Group Total -->
                    <tr class="total-row">
                        <td style="text-align: right;">Total</td>
                        <td style="text-align: right;">{{ number_format($expenses['overall_total'], 2, '.', ',') }}</td>
                    </tr>
            </tbody>
        </table>
    </td>
    <td style="width: 33%; padding-right: 5px;">
        <!-- Advances Table -->
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">
                        <h2 style="margin:5px 0;margin-top:3px;padding:0;" class="header">Advances</h2>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left;">Name</th>
                    <th style="text-align: right;width: 80px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic Advances Data -->
                @foreach ($advances['data'] as $advance)
                <tr>
                    <td style="text-align: left;">{{ $advance['salary_name'] }}</td>
                    <td style="text-align: right;">{{ number_format($advance['total_amount'], 2, '.', ',') }}</td>
                </tr>
                @endforeach
                <!-- Empty Rows if less than 10 -->
                @for ($i = count($advances['data']); $i < 8; $i++)
                    <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                    @endfor
                    <!-- Group Total -->
                    <tr class="total-row">
                        <td style="text-align: right;">Total</td>
                        <td style="text-align: right;">{{ number_format($advances['overall_total'], 2, '.', ',') }}</td>
                    </tr>
            </tbody>
        </table>
    </td>
    </tr>

    @php

    $overall_total_incomes = number_format($incomes['overall_total'], 2, '.', ',');
    $overall_total_expenses = number_format($expenses['overall_total'], 2, '.', ',');
    $overall_total_profits = $incomes['overall_total'] - $expenses['overall_total'];
    $overall_individual_profit = $overall_total_profits/2;

    $mehulExpense = collect($expenses['data'])->where('name', 'Mehul')->sum('total_amount');
    $mehulIncomes = collect($incomes['data'])->whereIn('name', ['Mehul','Mehul HUF'])->sum('total_amount');
    $mehulSalary = collect($advances['data'])->where('salary_name', 'Mehul Salary')->sum('total_amount');
    $mehulFrofit = ($overall_individual_profit + $mehulExpense) - ($mehulIncomes + $mehulSalary);

    $bhavinExpense = collect($expenses['data'])->where('name', 'Bhavin')->sum('total_amount');
    $bhavinIncomes = collect($incomes['data'])->whereIn('name', ['Bhavin'])->sum('total_amount');
    $bhavinSalary = collect($advances['data'])->where('salary_name', 'Bhavin Salary')->sum('total_amount');
    $bhavinFrofit = ($overall_individual_profit + $bhavinExpense) - ($bhavinIncomes + $bhavinSalary);


    @endphp


    <tr>
        <td style="width: 100%; padding-right: 5px;padding-left: 5px;" colspan="3">
            <table class="full-width-table">
                <thead>
                    <tr>
                        <th colspan="2">Company Profits</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <b>
                                ({{ $overall_total_incomes }} - {{ $overall_total_expenses }}) = {{ number_format($overall_total_profits, 2, '.', ','); }}
                            </b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 100%; padding-right: 5px;padding-left: 5px;" colspan="3">
            <table class="full-width-table">
                <thead>
                    <tr>
                        <th style="width: 80px;"></th>
                        <th style="width: 150px;text-align: right;">Individual Profit</th>
                        <th style="text-align: right;">Expense</th>
                        <th style="text-align: right;">Income + Advance</th>
                        <th style="width: 80px;text-align: right;">Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left;"><b>Mehul</b></td>
                        <td style="text-align: right;">{{ number_format($overall_individual_profit, 2, '.', ','); }}</td>
                        <td style="text-align: right;">{{ number_format($mehulExpense, 2, '.', ','); }}</td>
                        <td style="text-align: right;">({{ number_format($mehulIncomes, 2, '.', ','); }} + {{ number_format($mehulSalary, 2, '.', ','); }})</td>
                        <td style="text-align: right;">{{ number_format($mehulFrofit, 2, '.', ','); }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><b>Bhavin</b></td>
                        <td style="text-align: right;">{{ number_format($overall_individual_profit, 2, '.', ','); }}</td>
                        <td style="text-align: right;">{{ number_format($bhavinExpense, 2, '.', ','); }}</td>
                        <td style="text-align: right;">({{ number_format($bhavinIncomes, 2, '.', ','); }} + {{ number_format($bhavinSalary, 2, '.', ','); }})</td>
                        <td style="text-align: right;">{{ number_format($bhavinFrofit, 2, '.', ','); }}</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

    @php

    $mehul_opening_balance = $opening_balances->mehul ?? 0;
    $bhavin_opening_balance = $opening_balances->bhavin ?? 0;

    $carry_forword_profit_mehul = $carry_forword_profit['mehul_profit'];
    $carry_forword_profit_bhavin = $carry_forword_profit['bhavin_profit'];

    $total_profit_mehul = $mehulFrofit + $carry_forword_profit_mehul;
    $total_profit_bhavin = $bhavinFrofit + $carry_forword_profit_bhavin;

    if($carry_forword_profit_mehul === $mehul_opening_balance){
    $carry_forword_profit_mehul = 0;
    }
    if($carry_forword_profit_bhavin === $bhavin_opening_balance){
    $carry_forword_profit_bhavin = 0;
    }

    @endphp

    <tr>
        <td style="width: 100%; padding-right: 5px;padding-left: 5px;" colspan="3">
            <table class="full-width-table">
                <thead>
                    <tr>
                        <th colspan="5" style="width: 80px;">Overall Profits</th>
                    </tr>
                    <tr>
                        <th style="width: 80px;"></th>
                        <th style="width: 150px;text-align: right;">Opening Balance</th>
                        <th style="width: 112px;text-align: right;">Profit</th>
                        <th style="text-align: right;">Carryforward Profit</th>
                        <th style="width: 80px;text-align: right;">Total Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left;"><b>Mehul</b></td>
                        <td style="text-align: right;">{{ number_format($mehul_opening_balance, 2, '.', ','); }}</td>
                        <td style="text-align: right;">{{ number_format($mehulFrofit, 2, '.', ','); }}</td>
                        <td style="text-align: right;">{{ number_format($carry_forword_profit_mehul, 2, '.', ','); }}</td>
                        <td style="text-align: right;">{{ number_format($total_profit_mehul, 2, '.', ','); }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><b>Bhavin</b></td>
                        <td style="text-align: right;">{{ number_format($bhavin_opening_balance, 2, '.', ','); }}</td>
                        <td style="text-align: right;">{{ number_format($bhavinFrofit, 2, '.', ','); }}</td>
                        <td style="text-align: right;">{{ number_format($carry_forword_profit_bhavin, 2, '.', ','); }}</td>
                        <td style="text-align: right;">{{ number_format($total_profit_bhavin, 2, '.', ','); }}</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </table>

</body>

</html>