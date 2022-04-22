<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = DB::collection('customers')->get()->toArray();
        //dd($customers);
        foreach($customers as $customer){
            Transaction::factory()
                ->count(5)
                ->create([
                    "customer_id" => $customer['_id']->__toString()
                ]);
        }
    }
}
