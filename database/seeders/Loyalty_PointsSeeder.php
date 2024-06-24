<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoyaltyPoint;
use Faker\Generator as Faker;

class Loyalty_PointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 0; $i <= 10; $i++) {
            $new_loyalty_point = new LoyaltyPoint();
            $new_loyalty_point->total_points = $faker->numberBetween(10, 100);
            $new_loyalty_point->save();
        }
    }
}
