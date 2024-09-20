<?php

namespace App\Helpers;

use App\Models\Sale;

class Helper
{
    public static function generateSaleBillNo($prefix = 'INV', $date = null)
    {
        // If no date is provided, use the current date
        $date = $date ? strtotime($date) : time();

        $year = date('Y', $date);
        $month = date('m', $date);

        // Determine the start year of the financial year
        $startOfFinancialYear = ($month >= 4) ? $year : $year - 1;

        // Create the financial year in the format "2024-25"
        $endOfFinancialYear = substr(($startOfFinancialYear + 1), 2);
        $financialYear = "{$startOfFinancialYear}-{$endOfFinancialYear}";
        $invoicePrefix = $prefix . "-{$financialYear}";

        // Get the last invoice ID for the current financial year
        $lastInvoice = Sale::where('bill_no', 'LIKE', "%" . "$invoicePrefix" . "%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            // Increment the last invoice number
            $lastInvoiceNumber = (int) explode('-', $lastInvoice->bill_no)[3];
            $newInvoiceNumber = $lastInvoiceNumber + 1;
        } else {
            // Start with 1 if no invoice exists for the current financial year
            $newInvoiceNumber = 1;
        }

        // Generate the new invoice ID
        $newInvoiceId = "{$invoicePrefix}-{$newInvoiceNumber}";

        return $newInvoiceId;
    }

    public static function amountToWords($number)
    {
        $words = [
            '0' => '',
            '1' => 'one',
            '2' => 'two',
            '3' => 'three',
            '4' => 'four',
            '5' => 'five',
            '6' => 'six',
            '7' => 'seven',
            '8' => 'eight',
            '9' => 'nine',
            '10' => 'ten',
            '11' => 'eleven',
            '12' => 'twelve',
            '13' => 'thirteen',
            '14' => 'fourteen',
            '15' => 'fifteen',
            '16' => 'sixteen',
            '17' => 'seventeen',
            '18' => 'eighteen',
            '19' => 'nineteen',
            '20' => 'twenty',
            '30' => 'thirty',
            '40' => 'forty',
            '50' => 'fifty',
            '60' => 'sixty',
            '70' => 'seventy',
            '80' => 'eighty',
            '90' => 'ninety'
        ];

        if ($number < 21) {
            return $words[$number];
        } elseif ($number < 100) {
            return $words[10 * floor($number / 10)] . '' . $words[$number % 10];
        } elseif ($number < 1000) {
            return $words[floor($number / 100)] . ' hundred ' . self::amountToWords($number % 100);
        } elseif ($number < 1000000) {
            return self::amountToWords(floor($number / 1000)) . ' thousand ' . self::amountToWords($number % 1000);
        } elseif ($number < 1000000000) {
            return self::amountToWords(floor($number / 1000000)) . ' million ' . self::amountToWords($number % 1000000);
        } elseif ($number < 1000000000000) {
            return self::amountToWords(floor($number / 1000000000)) . ' billion ' . self::amountToWords($number % 1000000000);
        }
    }
}
