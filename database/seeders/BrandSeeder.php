<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Fiat',
            'Alfa Romeo',
            'Lancia',
            'Ferrari',
            'Lamborghini',
            'Maserati',
            'BMW',
            'Mercedes-Benz',
            'Audi',
            'Volkswagen',
            'Toyota',
            'Honda',
            'Ford',
            'Renault',
            'Peugeot',
            'Citroën',
            'Opel',
            'Tesla',
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand]);
        }

        $this->command->info('Brand importate con successo!');
    }
}
