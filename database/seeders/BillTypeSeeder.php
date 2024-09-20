<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bill_types')->insert([
            [
                'alias' => 'TI',
                'text' => 'Tax Invoice',
            ],
            [
                'alias' => 'BS',
                'text' => 'Bill Of Supply',
            ],
            [
                'alias' => 'PF',
                'text' => 'Pro Forma Invoice',
            ],
            [
                'alias' => 'IN',
                'text' => 'Invoice',
            ],
            [
                'alias' => 'EX',
                'text' => 'Export',
            ],
        ]);
    }
}
