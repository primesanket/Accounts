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
            /* Smaller font size */
            margin: 10px;
            /* Smaller margin */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px;
            /* Smaller padding */
            text-align: center;
            font-size: 12px;
        }

        .table th,
        .total-row td {
            background-color: #B0C4DE;
            font-size: 12px;
            /* Adjusted font size for header */
        }

        .table tr:nth-child(even) {
            /* background-color: #F2F2F2; */
        }

        .table tr:nth-child(odd) {
            /* background-color: #D3D3D3; */
        }

        .total-row {
            font-weight: bold;
            font-size: 12px;
            /* Adjusted font size for totals */
        }

        .header {
            text-align: center;
            font-size: 14px;
            /* Adjusted header font size */
            margin-bottom: 10px;
            /* Smaller margin below header */
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="5">
                    <h2 style="margin:5px 0;margin-top:3px;padding:0;">Expense Report</h2>
                </th>
            </tr>
            <tr>
                <th style="text-align: left;">Done By</th>
                <th style="text-align: left;">Expense Type</th>
                <th style="">Date</th>
                <th style="">Remark</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grouped_data as $expense)
            @php $rowCount = count($expense['expenses']); @endphp
            @foreach($expense['expenses'] as $index => $item)
            <tr>
                @if ($index === 0)
                <!-- First row, include the rowspan -->
                <td rowspan="{{ $rowCount }}" style="text-align: left;">{{ $expense['expense_account'] }}</td>
                @endif
                <td style="text-align: left;">{{ $item['expense_type'] }}</td>
                <td style="">{{ $item['expense_date'] }}</td>
                <td style="text-align: left;">{{ $item['remark'] }}</td>
                <td style="text-align: right;">{{ number_format($item['amount'], 2) }}</td>
            </tr>
            @endforeach
            <!-- Group Total -->
            <tr class="total-row">
                <td colspan="4" style="text-align: right;background-color:#D3D3D3;">Total for {{ $expense['expense_account'] }}</td>
                <td style="text-align: right;background-color:#D3D3D3;">{{ number_format($expense['total_expenses'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <!-- Overall Total -->
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total</td>
                <td style="text-align: right;">{{ number_format($overall_total_expenses, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>