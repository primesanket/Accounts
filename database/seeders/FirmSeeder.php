<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FirmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('firms')->insert([
            [
                'firm_name' => 'Primento Technologies',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
