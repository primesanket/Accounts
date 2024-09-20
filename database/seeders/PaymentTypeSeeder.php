<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_types')->insert([
            [
                'text' => 'Cash',
            ],
            [
                'text' => 'Google Pay',
            ],
            [
                'text' => 'Bank',
            ],
        ]);
    }
}
