<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'acc_number',
        'name'
    ];

    public function meterReadings() {
        return $this->hasMany(MeterReadings::class);
    }
}
