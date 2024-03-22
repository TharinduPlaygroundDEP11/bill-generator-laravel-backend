<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MeterReading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeterReadingController extends Controller
{
    public function save(Request $request) {
        Validator::extend('before_today', function($attribute, $value, $parameters, $validator) {
            $today = now()->startOfDay();
            $selectedDate = Carbon::parse($value)->startOfDay();
            return $selectedDate->lt($today);
        });

        $validator = Validator::make($request->all(), [
            'number' => 'required|exists:customers,account_number',
            'date' => 'required|date|before_today',
            'value' => 'required|integer'
        ], [
            'number.exists' => 'Account number does not exists',
            'date.required' => 'Reading date can not be empty',
            'date.before_today' => 'Reading date can not be a future date',
            'value.required' => 'Reading value can not be empty',
            'value.integer' => 'Reading value should be an integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        $customer = Customer::where('account_number', $validatedData['number'])->first();

        $customerId = $customer->id;

        $meterReading = MeterReading::create([
            'customer_id' => $customerId,
            'date' => $validatedData['date'],
            'value' => $validatedData['value']
        ]);

        return response()->json([
            'message' => 'Reading added successfully',
            'reading' => $meterReading
        ], 201);
    }
}