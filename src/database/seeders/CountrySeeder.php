<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Ukraine', 'code' => 'UA', 'region' => 'Europe', 'description' => 'Kyiv, Black Sea access'],
            ['name' => 'Germany', 'code' => 'DE', 'region' => 'Europe', 'description' => 'Berlin, strong economy'],
            ['name' => 'Turkey',  'code' => 'TR', 'region' => 'Asia/Europe', 'description' => 'Istanbul, tourism hub'],
        ];

        foreach ($items as $i) {
            Country::updateOrCreate(['code' => $i['code']], $i);
        }
    }
}
