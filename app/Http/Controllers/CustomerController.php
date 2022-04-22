<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function fetchAll()
    {
        $customers = [];
        $customers = Customer::get()->toArray();

        return response()->json([
            "error" => false,
            "message" => "OK",
            "result" => $customers
        ]);
    }

    public function fetchOne($customerId)
    {
        $customer = Customer::where("_id", $customerId)
            ->first()?->toArray();

        if(!isset($customer)){
            return response()->json([
                "error" => true,
                "message" => "User not found"
            ]);
        }

        return response()->json([
            "error" => false,
            "message" => "OK",
            "result" => $customer
        ]);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        try {
            $validated = $request->validate([
                "name" => "required",
                "nic" => "required"
            ]);

            $customer = new Customer();
            $customer->customer_name = $validated['name'];
            $customer->nic = $validated['nic'];
            $result = $customer->save();

            if($result){
                return response()->json([
                    "error" => false,
                    "message" => "Customer added",
                    "result" => "CREATED"
                ]);
            }
    
            return response()->json([
                "error" => true,
                "message" => "Something went wrong",
                "result" => "FAILED"
            ]);

        } catch (Exception $ex) {
           return $ex;
        }
    }

    public function update($customerId, Request $request)
    {
        try {
            $validated = $request->validate([
                "name" => "string",
                "nic" => "string"
            ]);
    
            $customerFound = Customer::where("_id", $customerId)->first();
    
            if(!isset($customerFound)){
                return response()->json([
                    "error" => false,
                    "message" => "User not found",
                ]);
            }
    
            $customerFound->customer_name = $validated['name'];
            $customerFound->customer_nic = $validated['nic'];
    
            $result = $customerFound->save();
    
            if($result){
                return response()->json([
                    "error" => false,
                    "message" => "Customer updated",
                    "result" => "UPDATED"
                ]);
            }
    
            return response()->json([
                "error" => true,
                "message" => "Something went wrong",
                "result" => "FAILED"
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}

