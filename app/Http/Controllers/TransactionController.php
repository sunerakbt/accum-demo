<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function fetchAll()
    {
        $transactions = [];

        try {
            $transactions = Transaction::get()->toArray();
            return response()->json([
                "error" => false,
                "message" => "OK",
                "result" => $transactions
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function fetchOne($transactionId)
    {
        try {
            $transaction = Transaction::where("_id", $transactionId)
                ->with('customer')
                ->first();

            if (!isset($transaction)) {
                return response()->json([
                    "error" => true,
                    "message" => "Transaction not found"
                ]);
            }

            return response()->json([
                "error" => false,
                "message" => "OK",
                "result" => $transaction
            ]);
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function process()
    {
        $unprocessedTransactions = [];

        $session = DB::getMongoClient()->startSession();
        $session->startTransaction();
        try {
            $unprocessedTransactions = DB::collection('transactions')
            ->where("is_proccessed", 0)
            ->get()
            ->toArray();

            //dd($unprocessedTransactions);
            $accumulations = $processedId = [];
            if(!empty($unprocessedTransactions)){
                
                foreach($unprocessedTransactions as $transaction){
                    $cid = $transaction['customer_id'];
                    if(!empty($accumulations) && array_key_exists($cid, $accumulations)){
                        Log::info(" found");

                        $accumulatedPoints = $this->accumulate(doubleval($transaction['amount']));
                        $currentPoints = $accumulations[$cid];
                        $updatedPoints = $currentPoints + $accumulatedPoints;
                        $accumulations[$cid] = $updatedPoints;

                    }else{
                        Log::info("not found");
                        $accumulations[$cid] = $this->accumulate(doubleval($transaction['amount']));
                    }
                    //dd($accumulations);
                    array_push($processedId, $transaction['_id']);
                }
                
                //dd($accumulations, $processedId);
                Transaction::whereIn("_id", $processedId)->update([
                    "is_proccessed" => 1
                ]);

                foreach($accumulations as $k => $acc){
                    $customer = Customer::where("_id", $k)->first();
                    $customer->point_balancefff = $customer->point_balance + $accumulations[$k];
                    $customer->save();
                }

                //$session->commitTransaction();
                return response()->json([
                    "error" => true,
                    "message" => "Transactions processed successfully"
                ]);
            }
            return response()->json([
                "error" => true,
                "message" => "No transactions to process"
            ]);
        } catch (Exception $ex) {
            $session->abortTransaction();
            return $ex;
        }
    }

    private function accumulate($amount)
    {
        /*
        * Accumulation Rules
        * 1 - 100 => 1 point 
        * 100 - 500 => 1.5 points 
        * 500 - 1000 => 1.75 points 
        * 1000 - 5000 => 2 points
        * 5000+ => 3 points
        */
        $accumulatedPoints = 0;

        switch ($amount) {
            case $amount > 0 && $amount < 100:
                $accumulatedPoints = 1;
                break;
            case $amount > 100 && $amount < 500:
                $accumulatedPoints = 1.5;
                break;
            case $amount > 500 && $amount < 1000:
                $accumulatedPoints = 1.75;
                break;
            case $amount > 1000 && $amount < 5000:
                $accumulatedPoints = 2;
                break;
            case $amount > 5000:
                $accumulatedPoints = 3;
                break;
            default:
                $accumulatedPoints = 0;
                break;
        }

        return doubleval($accumulatedPoints);
    }
}
