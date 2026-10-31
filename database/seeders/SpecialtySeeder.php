<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Cardiology',
            'Orthopedics',
            'Ophthalmology',
            'Psychiatry',
            'Neurology',
            'Dermatology',
            'Pediatrics',
            'Internal Medicine',
            'Otolaryngology',
            'Dentistry',
            'Radiology',
            'Physiotherapy',
            'General Practice',
            'Midwifery',
        ];

        foreach ($names as $name) {
            Specialty::firstOrCreate(['name' => $name]);
        }
    }
}
