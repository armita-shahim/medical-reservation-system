<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = [
            ['name' => 'بیمارستان سینا', 'city' => 'تبریز', 'address' => 'خیابان آزادی'],
            ['name' => 'بیمارستان اسدآبادی', 'city' => 'تبریز', 'address' => 'خیابان بهار'],
            ['name' => 'بیمارستان امام رضا', 'city' => 'تبریز', 'address' => 'خیابان دانشگاه'],
            ['name' => 'بیمارستان فطمیه', 'city' => 'شبستر', 'address' => 'بلوار بسیج'],
            ['name' => 'بیمارستان شهید مدنی', 'city' => 'آذرشهر', 'address' => 'خیابان امام'],
            ['name' => 'بیمارستان فارابی', 'city' => 'ملکان', 'address' => 'بلوار آنا'],
        ];

        foreach ($hospitals as $hospital) {
            Hospital::firstOrCreate(['name' => $hospital['name'], 'city' => $hospital['city']], $hospital);
        }
    }
}
