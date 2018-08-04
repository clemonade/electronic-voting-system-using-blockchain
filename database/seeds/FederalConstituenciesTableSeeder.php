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
                'name' => 'Bukit Mertajam',
                'state' => '07',
            ],
            [
                'code' => 'P.002',
                'name' => 'Batu Kawan',
                'state' => '07',
            ],
            [
                'code' => 'P.003',
                'name' => 'Petaling Jaya',
                'state' => '10',
            ],
            [
                'code' => 'P.004',
                'name' => 'Damansara',
                'state' => '10',
            ],
        ]);

        DB::table('federalconstituencies')->insert([
            [
                'code' => 'P.005',
                'name' => 'Bukit Bintang',
                'state' => '14',
                'nostate' => '1',
            ],
            [
                'code' => 'P.006',
                'name' => 'Cheras',
                'state' => '14',
                'nostate' => '1',
            ],
        ]);
    }
}
