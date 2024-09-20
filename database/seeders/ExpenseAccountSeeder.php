<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expense_accounts')->insert([
            [
                'name' => 'Prime',
            ],
            [
                'name' => 'Primento',
            ],
            [
                'name' => 'Primero',
            ],
            [
                'name' => 'Bhavin',
            ],
            [
                'name' => 'Mehul',
            ],
            [
                'name' => 'Mehul HUF',
            ],
            [
                'name' => 'Shital',
            ],
            [
                'name' => 'Nikita',
            ],
        ]);
    }
}
