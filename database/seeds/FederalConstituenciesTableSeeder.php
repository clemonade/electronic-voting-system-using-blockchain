<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FederalConstituenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('federalconstituencies')->insert([
            [
                'code' => 'P.001',
                'name' => 'Permatang Pauh',
                'state' => '14',
            ],
            [
                'code' => 'P.002',
                'name' => 'Bukit Mertajam',
                'state' => '14',
            ],
            [
                'code' => 'P.003',
                'name' => 'Batu Kawan',
                'state' => '14',
            ],
            [
                'code' => 'P.004',
                'name' => 'Bukit Bendera',
                'state' => '07',
            ],
            [
                'code' => 'P.005',
                'name' => 'Jelutong',
                'state' => '07',
            ]
        ]);
    }
}
