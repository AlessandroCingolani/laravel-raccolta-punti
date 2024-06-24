<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Purchase;

class CustomerPurchaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchases = Purchase::all();
        foreach ($purchases as $purchase) {
            $customer_id = Customer::inRandomOrder()->first()->id;
            $purchase->customer_id = $customer_id;
            $purchase->save();
        }
    }
}
