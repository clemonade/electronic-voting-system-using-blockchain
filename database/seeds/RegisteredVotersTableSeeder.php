<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegisteredVotersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('registeredvoters')->insert([
            [
                'name' => 'AAA',
                'nric' => '111111111111',
                'gender' => 'M',
                'federalconstituency' => '1',
                'stateconstituency' => '1',
                'state' => '07',
                'voted' => '0',
            ],
            [
                'name' => 'BBB',
                'nric' => '222222222222',
                'gender' => 'F',
                'federalconstituency' => '1',
                'stateconstituency' => '1',
                'state' => '07',
                'voted' => '0',
            ],
            [
                'name' => 'CCC',
                'nric' => '333333333333',
                'gender' => 'M',
                'federalconstituency' => '1',
                'stateconstituency' => '1',
                'state' => '07',
                'voted' => '0',
            ],
            [
                'name' => 'DDD',
                'nric' => '444444444444',
                'gender' => 'F',
                'federalconstituency' => '1',
                'stateconstituency' => '1',
                'state' => '07',
                'voted' => '0',
            ],
            [
                'name' => 'EEE',
                'nric' => '555555555555',
                'gender' => 'M',
                'federalconstituency' => '1',
                'stateconstituency' => '1',
                'state' => '07',
                'voted' => '0',
            ],
        ]);

        DB::table('registeredvoters')->insert([
            [
                'name' => 'FFF',
                'nric' => '666666666666',
                'gender' => 'F',
                'federalconstituency' => '6',
                'state' => '14',
                'voted' => '0',
            ],
            [
                'name' => 'GGG',
                'nric' => '777777777777',
                'gender' => 'M',
                'federalconstituency' => '6',
                'state' => '14',
                'voted' => '0',
            ],
            [
                'name' => 'HHH',
                'nric' => '888888888888',
                'gender' => 'F',
                'federalconstituency' => '6',
                'state' => '14',
                'voted' => '0',
            ],
            [
                'name' => 'III',
                'nric' => '999999999999',
                'gender' => 'M',
                'federalconstituency' => '6',
                'state' => '14',
                'voted' => '0',
            ],
        ]);
    }
}
