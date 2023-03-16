<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{



    //to transfer amount
    public function transfer(Request $request)
    {
        // Get the data from the request
        $from_account_id = $request->input('from_account_id');
        $to_account_id = $request->input('to_account_id');
        $amount = $request->input('amount');



        // Start a database transaction
        DB::beginTransaction();

        try {
            // Load the from and to accounts for updating
            $from_account = Account::where('id', $from_account_id)->lockForUpdate()->first();
            $to_account = Account::where('id', $to_account_id)->lockForUpdate()->first();

            // Check if the from account has enough balance
            if ($from_account->balance < $amount) {
                return response()->json(['message' => 'Insufficient balance'], 400);
            }

            // Update the account balances
            $from_account->balance -= $amount;
            $to_account->balance += $amount;
            $from_account->save();
            $to_account->save();

            //insert the transaction into transaction table
            Transaction::create([
                'sender_account_id' => $from_account_id,
                'receiver_account_id' => $to_account_id,
                'amount' => $amount
            ]);



            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json(['message' => 'Transfer successful'], 200);
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollback();

            // Return an error response
            return response()->json(['message' => 'Transfer failed'], 500);
        }
    }



    //to show all transactions
    public function showAllTransaction(){
        $transactions = Transaction::all();
        if ($transactions->count() > 0) {
            return response()->json([
                'status' => 200,
                'transactions' => $transactions
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No record found'
            ], 404);
        }
    }


    //to show individual transaction
    public function showOneTransaction($id){
        $transaction = Transaction::find($id);
        if ($transaction) {
            return response()->json([
                'status' => 200,
                'transaction' => $transaction
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such transaction found'
            ], 404);
        }
    }
}
