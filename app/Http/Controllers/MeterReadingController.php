<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MeterReading;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeterReadingController extends Controller
{
    public function save(Request $request)
    {
        Validator::extend('not_future_date', function ($attribute, $value, $parameters, $validator) {
            $today = now()->startOfDay();
            $selectedDate = Carbon::parse($value)->startOfDay();
            return $selectedDate->lte($today);
        });

        Validator::extend('unique_date', function ($attribute, $value, $parameters, $validator) {
            $accountNumber = $validator->getData()['number'];
            $customer = Customer::where('account_number', $accountNumber)->first();
            if ($customer) {
                $customerId = $customer->id;
                $readingExist = MeterReading::where('customer_id', $customerId)
                    ->whereDate('date', $value)
                    ->exists();
                return !$readingExist;
            } else {
                return response()->json(['errors' => $validator->errors()], 404);
            }
        });

        $validator = Validator::make($request->all(), [
            'number' => 'required|exists:customers,account_number',
            'date' => 'required|date|not_future_date|unique_date',
            'value' => 'required|integer|gte:0'
        ], [
            'number.exists' => 'Account number does not exists',
            'date.required' => 'Reading date can not be empty',
            'date.not_future_date' => 'Reading date can not be a future date',
            'date.unique_date' => 'Already added a reading to this date',
            'value.required' => 'Reading value can not be empty',
            'value.integer' => 'Reading value should be an number',
            'value.gt' => 'Reading value can not be negative'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $validatedData = $validator->validated();

        $customer = Customer::where('account_number', $validatedData['number'])->first();

        $customerId = $customer->id;

        $previousReading = MeterReading::where('customer_id', $customerId)
            ->whereDate('date', '<', $validatedData['date'])
            ->orderByDesc('date')
            ->first();

        $nextReading = MeterReading::where('customer_id', $customerId)
            ->whereDate('date', '>', $validatedData['date'])
            ->orderBy('date')
            ->first();

        if ($previousReading && $validatedData['value'] < $previousReading->value) {
            return response()->json(['error' => 'Reading value should be larger than the previous value'], 422);
        }

        if ($nextReading && $validatedData['value'] > $nextReading->value) {
            return response()->json(['error' => 'Reading value should be less than the next value'], 422);
        }

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
