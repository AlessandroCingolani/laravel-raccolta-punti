<?php

namespace Database\Seeders;

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
        for ($i = 0; $i <= 10; $i++) {
            $new_purchase = new Purchase();
            $new_purchase->amount = $faker->randomFloat(2, 1, 100);
            $new_purchase->points_earned = $faker->numberBetween(1, 10);
            $new_purchase->save();
        }
    }
}
