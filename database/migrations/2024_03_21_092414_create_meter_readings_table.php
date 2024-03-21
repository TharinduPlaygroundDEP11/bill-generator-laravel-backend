<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->date('reading_date');
            $table->integer('reading_value');
            $table->decimal('fixed_charge', 8, 2);
            $table->decimal('first_range_billed_amount', 8, 2);
            $table->decimal('second_range_billed_amount', 8, 2);
            $table->decimal('third_range_billed_amount', 8, 2);
            $table->decimal('total_billed_amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};
