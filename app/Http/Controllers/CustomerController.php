<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MeterReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function get ($number) {
        $data = ['number' => $number];

        $validator = Validator::make($data, [
            'number' => 'required|exists:customers,account_number'
        ], [
            'number.exists' => 'Account number does not exists!'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        $customer = Customer::where('account_number', $validatedData['number'])->first();

        $customerId = $customer->id;
        $customerName = $customer->name;

        $lastReading = MeterReading::where('customer_id', $customerId)->orderBy('');
    }
}
