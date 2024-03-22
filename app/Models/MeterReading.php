<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'date',
        'value',
    ];

    public function customers() {
        return $this->belongsTo(Customer::class);
    }
}
