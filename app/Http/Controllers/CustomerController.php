<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MeterReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function get($number)
    {
        $data = ['number' => $number];

        $validator = Validator::make($data, [
            'number' => 'required|exists:customers,account_number'
        ], [
            'number.exists' => 'Account number does not exists!'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        $customer = Customer::where('account_number', $validatedData['number'])->first();

        $customerId = $customer->id;
        $customerName = $customer->name;

        $lastReading = MeterReading::where('customer_id', $customerId)
            ->orderBy('date', 'desc')->first();

        $previousReading = MeterReading::where('customer_id', $customerId)
            ->where('date', '<', $lastReading->date)
            ->orderBy('date', 'desc')->first();

        if (!$previousReading) {
            return response()->json(['error'=>'No previous readings'], 404);
        }

        $totalUnits = $lastReading->value - $previousReading->value;

        $firstRangeAmount = 0;
        $secondRangeAmount = 0;
        $thirdRangeAmount = 0;
        $totalAmount = $this->getFixedAmount($totalUnits);

        if ($totalAmount === 500) {
            $firstRangeAmount = $totalUnits * 20;
        }

        if ($totalAmount === 1000) {
            $firstRangeAmount = 600;
            $secondRangeAmount = ($totalUnits - 30) * 35;
        }

        if ($totalAmount === 1500) {
            $firstRangeAmount = 600;
            $secondRangeAmount = 1050;
            $thirdRangeAmount = ($totalUnits - 60) * 40;
        }

        $totalAmount = $totalAmount + $firstRangeAmount + $secondRangeAmount + $thirdRangeAmount;
        
        echo($totalAmount);
    }

    private function getFixedAmount($totalUnits) {
        if ($totalUnits <= 30){
            return 500;
        } elseif ($totalUnits <= 60) {
            return 1000;
        } else {
            return 1500;
        }       
    }
}
