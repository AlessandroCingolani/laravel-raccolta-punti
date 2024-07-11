<?php

namespace Database\Seeders;

use App\Functions\Helper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Purchase;
use Faker\Generator as Faker;

class PurchasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $today = date('Y-m-d');
        for ($i = 0; $i <= 1000; $i++) {
            $new_purchase = new Purchase();
            $new_purchase->amount = $faker->randomFloat(2, 1, 100);
            $new_purchase->points_earned = Helper::generatePoints($new_purchase->amount);
            $new_purchase->created_at = $faker->dateTimeBetween('2024-01-01', $today);
            $new_purchase->save();
        }
    }
}
