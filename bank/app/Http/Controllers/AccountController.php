<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    // Create a new account for a customer


    public function store(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'balance' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        } else {

            $account = Account::create([
                'customer_id' => $customer->id,
                'balance' => $request->balance
            ]);

            if ($account) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Account created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Something went wrong!'
                ], 500);
            }
        }
    }




    //Showing specific account details

    public function show($id)
    {
        $account = Account::find($id);
        if ($account) {
            return response()->json([
                'status' => 200,
                'account' => $account
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such account found'
            ], 404);
        }
    }




    //retrieve account Balance for an specific account

    public function showBalance($id)
    {
        $account = Account::find($id);
        if ($account) {
            return response()->json([
                'status' => 200,
                'Balance' => $account->balance
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No such account found'
            ], 404);
        }
    }
}
