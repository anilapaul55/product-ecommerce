<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
       public function run(): void
    {
        $colors = [
            ['name' => 'Red'],
            ['name' => 'Blue'],
            ['name' => 'Black'],
            ['name' => 'White'],
            ['name' => 'Green'],
        ];

        DB::table('colors')->insert($colors);
    }
}
