<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Generator as Faker;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 0; $i <= 10; $i++) {
            $new_customer = new Customer();
            $new_customer->name = $faker->firstName();
            $new_customer->surname = $faker->lastName();
            $new_customer->email = $faker->email();
            $new_customer->phone = $faker->phoneNumber();
            $new_customer->save();
        }
    }
}
