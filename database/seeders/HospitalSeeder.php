<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        $hospitals = [
            [
                'name' => 'Sina Hospital',
                'city' => 'Tabriz',
                'address' => 'Azadi Street',
            ],
            [
                'name' => 'Asadabadi Hospital',
                'city' => 'Tabriz',
                'address' => 'Bahar Street',
            ],
            [
                'name' => 'Imam Reza Hospital',
                'city' => 'Tabriz',
                'address' => 'University Street',
            ],
            [
                'name' => 'Fatemiyeh Hospital',
                'city' => 'Shabestar',
                'address' => 'Basij Boulevard',
            ],
            [
                'name' => 'Shahid Madani Hospital',
                'city' => 'Azarshahr',
                'address' => 'Imam Street',
            ],
            [
                'name' => 'Farabi Hospital',
                'city' => 'Malekan',
                'address' => 'Ana Boulevard',
            ],
        ];

        foreach ($hospitals as $hospital) {
            Hospital::firstOrCreate(['name' => $hospital['name'], 'city' => $hospital['city']], $hospital);
        }
    }
}
