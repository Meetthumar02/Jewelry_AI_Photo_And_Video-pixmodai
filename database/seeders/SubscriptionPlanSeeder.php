<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('subscription_plans')->insert([
        ['name' => '1 Month', 'duration_months' => 1, 'credits' => 1000, 'price' => 849],
        ['name' => '3 Months', 'duration_months' => 3, 'credits' => 3000, 'price' => 2249],
        ['name' => '6 Months', 'duration_months' => 6, 'credits' => 6000, 'price' => 3899],
        ['name' => '1 Year', 'duration_months' => 12, 'credits' => 12000, 'price' => 6599],
    ]);
    }
}
