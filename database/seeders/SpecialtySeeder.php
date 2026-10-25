<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'قلب و عروق',
            'ارتوپدی',
            'چشمپزشکی',
            'روانپزشکی',
            'مغز و اعصاب',
            'پوست و مو',
            'کودکان',
            'داخلی',
            'گوش، حلق و بینی',
            'دندانپزشکی',
            'رادیولوژی',
            'فیزیوتراپی',
            'پزشک عمومی',
            'مامایی'
        ];

        foreach ($names as $name) {
            Specialty::firstOrCreate(['name' => $name]);
        }
    }
}
