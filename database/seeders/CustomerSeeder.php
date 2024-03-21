<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::truncate();
        
        $csvFile = fopen(base_path("database/data/customers.csv"), "r");

        $firstLine = true;

        while (($data = fgetcsv($csvFile, 1000, ",")) !== false) {
            if (!$firstLine) {
                Customer::create([
                    'acc_number' => $data[0],
                    'name' => $data[1]
                ]);
            }
            $firstLine = false;
        };
        fclose($csvFile);
    }
}
