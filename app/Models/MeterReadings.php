<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterReadings extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'reading_date',
        'reading_value',
        'fixed_charge',
        'first_range_billed_amount',
        'second_range_billed_amount',
        'third_range_billed_amount',
        'total_billed_amount'
    ];

    public function customers() {
        return $this->belongsTo(Customer::class);
    }
}
