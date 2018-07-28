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
                'code' => 'P.001_N.01',
                'name' => 'Seberang Jaya',
                'state' => '14',
            ],
            [
                'code' => 'P.001_N.02',
                'name' => 'Penanti',
                'state' => '14',
            ],
            [
                'code' => 'P.002_N.03',
                'name' => 'Machang Bubok',
                'state' => '14',
            ],
            [
                'code' => 'P.002_N.04',
                'name' => 'Padang Lalang',
                'state' => '14',
            ],
            [
                'code' => 'P.003_N.05',
                'name' => 'Perai',
                'state' => '14',
            ],
            [
                'code' => 'P.003_N.06',
                'name' => 'Bukit Tengah',
                'state' => '14',
            ],
            [
                'code' => 'P.003_N.07',
                'name' => 'Bukit Tambun',
                'state' => '14',
            ],
            [
                'code' => 'P.004_N.01',
                'name' => 'Tanjong Bunga',
                'state' => '07',
            ],
            [
                'code' => 'P.004_N.02',
                'name' => 'Air Puteh',
                'state' => '07',
            ],
            [
                'code' => 'P.005_N.03',
                'name' => 'Datok Keramat',
                'state' => '07',
            ],
            [
                'code' => 'P.005_N.04',
                'name' => 'Sungai Pinang ',
                'state' => '07',
            ],
            [
                'code' => 'P.005_N.05',
                'name' => 'Batu Lancang',
                'state' => '07',
            ]
        ]);
    }
}
