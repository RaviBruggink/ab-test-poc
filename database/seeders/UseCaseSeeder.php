<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UseCase;

class UseCaseSeeder extends Seeder
{
    public function run(): void
    {
        $useCases = [
            'HR & communicatie',
            'Technische documentatie',
            'Code & reviewondersteuning',
            'Beleids- en teamtaken',
            'Marketing & content',
            'Onderzoek & analyse',
            'Klantenservice & support',
            'Testen & validatie',
        ];

        foreach ($useCases as $useCaseName) {
            UseCase::firstOrCreate(['name' => $useCaseName]);
        }
    }
}