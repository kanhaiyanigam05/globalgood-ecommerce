<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'India', 'code' => 'in', 'flag' => 'in.svg'],
            ['name' => 'Australia', 'code' => 'au', 'flag' => 'au.svg'],
            ['name' => 'Canada', 'code' => 'ca', 'flag' => 'ca.svg'],
            ['name' => 'United Kingdom', 'code' => 'gb', 'flag' => 'gb.svg'],
            ['name' => 'United States', 'code' => 'us', 'flag' => 'us.svg'],
            ['name' => 'United Arab Emirates', 'code' => 'ae', 'flag' => 'ae.svg'],
            ['name' => 'Japan', 'code' => 'jp', 'flag' => 'jp.svg'],
            ['name' => 'Malaysia', 'code' => 'my', 'flag' => 'my.svg'],
            ['name' => 'New Zealand', 'code' => 'nz', 'flag' => 'nz.svg'],
            ['name' => 'Norway', 'code' => 'no', 'flag' => 'no.svg'],
            ['name' => 'Singapore', 'code' => 'sg', 'flag' => 'sg.svg'],
            ['name' => 'South Korea', 'code' => 'kr', 'flag' => 'kr.svg'],
            ['name' => 'Switzerland', 'code' => 'ch', 'flag' => 'ch.svg'],
        ];

        foreach ($countries as $country) {
            \App\Models\Country::updateOrCreate(['code' => $country['code']], $country);
        }
    }
}
