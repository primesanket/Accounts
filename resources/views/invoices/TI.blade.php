<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoiceData['invoice_name'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: left;
            /* border-top: 5px solid #888; */
            position: relative;
            margin: 0;
            padding-top: 10px;
        }

        .header img {
            max-width: 250px;
        }

        .header .invoice_label {
            position: absolute;
            width: auto;
            height: 50px;
            /* background: red; */
            right: 0;
            text-align: right;
            border-left: 5px solid #888;
            padding-left: 15px;
        }

        .header .invoice_label h1 {
            margin-top: 8px;
            font-weight: 700;
            font-size: 26px;
        }

        .no-border-table,
        .no-border-table th,
        .no-border-table td {
            border: none;
        }

        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-section table {
            width: 100%;
            border: none;
        }

        .company-info,
        .invoice-info {
            width: 48%;
        }

        .company-info p,
        .invoice-info p,
        .bank-details p,
        .signature p {
            margin: 0;
            font-size: 12px;
            line-height: 1.6;
        }


        .company-info {
            font-size: 12px;
        }

        .company-info h3 {
            font-size: 17px;
        }

        .invoice-info {
            width: 230px;
            text-align: right;
            font-size: 12px;
            /* background: red; */
            position: absolute;
            right: 0;
            padding-bottom: 13px;
        }

        .invoice-info p {
            font-size: 14px;
        }

        .bill-to {
            width: 100%;
            margin-bottom: 20px;
            border-left: 5px solid #888;
            padding-left: 8px;
            margin-top: -10px;
        }


        .bill-to p {
            margin: 0;
            font-size: 15px;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .list-wrapper {
            /* background-color: red; */
            min-height: 302px;
        }

        .list td,
        .list th {
            text-align: center;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #e0e0e0;
        }

        .bank-details {
            font-size: 12px;
        }

        .signature {
            text-align: right;
            font-size: 12px;
            margin-top: 0px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 30px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="images/logo/primento.png" alt="Logo">
        <div class="invoice_label">
            <h1>{{ $invoiceData['invoice_name'] }}</h1>
        </div>
    </div>


    <div class="info-section">

        <table class="no-border-table">
            <tr>
                <td class="company-info">
                    <h3 style="margin-bottom: 0;margin: 0"><strong>{{ $invoiceData['company'] }}</strong></h3>
                    <p>GST NO: {{ $invoiceData['gst_no'] }}</p>
                    <p>PAN NO: {{ $invoiceData['pan_no'] }}</p>
                    <p style="margin-bottom: 5px;">LUT NO: {{ $invoiceData['lut_no'] }}</p>
                    <p style="line-height: 1.1;">{{ $invoiceData['address_1'] }}<br>{{ $invoiceData['address_2'] }}</p>
                    <p style="line-height: 1.3;">{{ $invoiceData['contact_no'] }}</p>
                </td>
                <td style="text-align: right;position:relative;">
                    <div class="invoice-info">
                        <p>Date: <strong>{{ $invoiceData['date'] }}</strong></p>
                        <p>Invoice No: <strong>{{ $invoiceData['invoice_no'] }}</strong></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="bill-to">
        <h3 style="padding: 0;margin:0;">Bill To:</h3>
        <p>{{ $invoiceData['bill_to']['name'] }}</p>
        <p>{{ $invoiceData['bill_to']['address'] }}</p>
        <p>{{ $invoiceData['bill_to']['gst_no'] }}</p>
    </div>

    <div class="list-wrapper">

        <div class="list">
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th>Description of Goods</th>
                        <th style="width: 80px;">HSN Code</th>
                        <th style="width: 30px;">Qty</th>
                        <th style="width: 80px;">Unit Price</th>
                        <th style="width: 80px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoiceData['items'] as $index => $item)
                    @if($loop->last)
                    <tr style="">
                        <td style="border-bottom:none;border-top:none;height:29px;">{{ $index + 1 }}</td>
                        <td style="border-bottom:none;border-top:none;text-align: left;padding:0 auto;"><strong>{{ $item['product_name'] }}</strong><br><span style="font-size: 10px;">{{ $item['description'] }}</span></td>
                        <td style="border-bottom:none;border-top:none;">{{ $item['hsn_code'] }}</td>
                        <td style="border-bottom:none;border-top:none;">{{ $item['quantity'] }}</td>
                        <td style="border-bottom:none;border-top:none;">{{ number_format($item['rate'], 2) }} </td>
                        <td style="border-bottom:none;border-top:none;">{{ number_format($item['amount'], 2) }}</td>
                    </tr>
                    @else
                    <tr>
                        <td style="border-bottom:none;border-top:none;height:29px;">{{ $index + 1 }}</td>
                        <td style="border-bottom:none;border-top:none;text-align: left;padding:0 auto;"><strong>{{ $item['product_name'] }}</strong><br><span style="font-size: 10px;">{{ $item['description'] }}</span></td>
                        <td style="border-bottom:none;border-top:none;">{{ $item['hsn_code'] }}</td>
                        <td style="border-bottom:none;border-top:none;">{{ $item['quantity'] }}</td>
                        <td style="border-bottom:none;border-top:none;">{{ number_format($item['rate'], 2) }} </td>
                        <td style="border-bottom:none;border-top:none;">{{ number_format($item['amount'], 2) }}</td>
                    </tr>
                    @endif
                    @endforeach
                    @for($i = 0; $i < (6 - sizeof($invoiceData['items'])); $i++)
                        <tr style="">
                        <td style="border-bottom:none;border-top:none;height:29px;"></td>
                        <td style="border-bottom:none;border-top:none;text-align: left;padding:0 auto;"></td>
                        <td style="border-bottom:none;border-top:none;"></td>
                        <td style="border-bottom:none;border-top:none;"></td>
                        <td style="border-bottom:none;border-top:none;"></td>
                        <td style="border-bottom:none;border-top:none;"></td>
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>

    </div>
    <table>
        <tbody>
            <tr>
                <td style="border-bottom:none;"></td>
                <td style="width: 224px;border-right:none;text-align: right;background-color:#e0e0e0;">Total Amount To Be Taxed</td>
                <td style="width: 80px;background-color:#e0e0e0;text-align: right;">{{ number_format($invoiceData['total_amount'], 2) }}</td>
            </tr>
            <tr>
                <td style="border-bottom:none;border-top:none;"></td>
                <td style="border-right:none;border-top:none;text-align: right;background-color:#e0e0e0;">CGST 9%</td>
                <td style="border-top:none;background-color:#e0e0e0;text-align: right;">{{ number_format($invoiceData['cgst'], 2) }}</td>
            </tr>
            <tr>
                <td style="border-bottom:none;border-top:none;"></td>
                <td style="border-right:none;border-top:none;text-align: right;background-color:#e0e0e0;">SGST 9%</td>
                <td style="border-top:none;background-color:#e0e0e0;text-align: right;">{{ number_format($invoiceData['sgst'], 2) }}</td>
            </tr>
            <tr>
                <td style="border-bottom:none;border-top:none;"></td>
                <td style="border-right:none;border-top:none;text-align: right;background-color:#e0e0e0;">IGST 18%</td>
                <td style="border-top:none;background-color:#e0e0e0;text-align: right;">{{ number_format($invoiceData['igst'], 2) }}</td>
            </tr>
            <tr>
                <td style="border-top:none;"></td>
                <td style="border-right:none;border-top:none;text-align: right;background-color:#e0e0e0;">Invoice Total</td>
                <td style="border-top:none;background-color:#e0e0e0;text-align: right;"><strong>{{ number_format($invoiceData['invoice_total'], 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="info-section">
        <table class="no-border-table">
            <tr>
                <td style="padding-left: 0;">
                    <div class="bank-details">
                        <p style="font-size: 14px;margin-bottom:10px">For NEFT/RTGS Transfer in below bank account:</p>
                        <p>Bank: {{ $invoiceData['bank_details']['bank'] }}</p>
                        <p>Account Name: {{ $invoiceData['bank_details']['account_name'] }}</p>
                        <p>Account Number: {{ $invoiceData['bank_details']['account_number'] }}</p>
                        <p>IFSC Code: {{ $invoiceData['bank_details']['ifsc_code'] }}</p>
                    </div>
                </td>
                <td style="text-align: right;padding-right: 0;">
                    <div class="signature">
                        <p>FOR, {{ $invoiceData['company'] }}</p>
                        <p style="margin-top: 65px;padding-top:13px;">DIRECTOR/AUTH.SIGN.</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Please contact us regarding payment options. thank you for your business.</p>
    </div>
</body>

</html>