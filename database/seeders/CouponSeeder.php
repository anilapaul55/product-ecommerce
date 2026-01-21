<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME100',
                'type' => 'fixed',
                'value' => 100,
                'expiry_date' => Carbon::now()->addDays(30),
                'min_amount' => 500,
            ],
            [
                'code' => 'SAVE10',
                'type' => 'percentage',
                'value' => 10,
                'expiry_date' => Carbon::now()->addDays(15),
                'min_amount' => 1000,
            ],
            [
                'code' => 'NEWUSER50',
                'type' => 'fixed',
                'value' => 50,
                'expiry_date' => null,
                'min_amount' => null,
            ],
        ];

        DB::table('coupons')->insert($coupons);
    }
}
