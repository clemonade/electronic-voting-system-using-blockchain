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
                'name' => 'Seberang Jaya',
                'state' => '14',
            ],
            [
                'code' => 'P.001/N.02',
                'name' => 'Penanti',
                'state' => '14',
            ],
            [
                'code' => 'P.002/N.03',
                'name' => 'Machang Bubok',
                'state' => '14',
            ],
            [
                'code' => 'P.002/N.04',
                'name' => 'Padang Lalang',
                'state' => '14',
            ],
            [
                'code' => 'P.003/N.05',
                'name' => 'Perai',
                'state' => '14',
            ],
            [
                'code' => 'P.003/N.06',
                'name' => 'Bukit Tengah',
                'state' => '14',
            ],
            [
                'code' => 'P.003/N.07',
                'name' => 'Bukit Tambun',
                'state' => '14',
            ],
            [
                'code' => 'P.004/N.01',
                'name' => 'Tanjong Bunga',
                'state' => '07',
            ],
            [
                'code' => 'P.004/N.02',
                'name' => 'Air Puteh',
                'state' => '07',
            ],
            [
                'code' => 'P.005/N.03',
                'name' => 'Datok Keramat',
                'state' => '07',
            ],
            [
                'code' => 'P.005/N.04',
                'name' => 'Sungai Pinang ',
                'state' => '07',
            ],
            [
                'code' => 'P.005/N.05',
                'name' => 'Batu Lancang',
                'state' => '07',
            ]
        ]);
    }
}
