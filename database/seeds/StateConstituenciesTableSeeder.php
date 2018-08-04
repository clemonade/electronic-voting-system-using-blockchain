<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateConstituenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stateconstituencies')->insert([
            [
                'code' => 'P.001/N.01',
                'name' => 'Machang Bubok',
                'state' => '07',
            ],
            [
                'code' => 'P.001/N.02',
                'name' => 'Padang Lalang',
                'state' => '07',
            ],
            [
                'code' => 'P.002/N.03',
                'name' => 'Perai',
                'state' => '07',
            ],
            [
                'code' => 'P.002/N.04',
                'name' => 'Bukit Tengah',
                'state' => '07',
            ],
            [
                'code' => 'P.003/N.01',
                'name' => 'Seri Setia ',
                'state' => '10',
            ],
            [
                'code' => 'P.003/N.02',
                'name' => 'Taman Medan',
                'state' => '10',
            ],
            [
                'code' => 'P.004/N.03',
                'name' => 'Kampung Tunku',
                'state' => '10',
            ],
            [
                'code' => 'P.004/N.04',
                'name' => 'Bandar Utama',
                'state' => '10',
            ],
        ]);
    }
}
