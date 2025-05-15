<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiModel;
use App\Models\UseCase;
use App\Models\ModelUseCaseScore;

class ModelUseCaseScoreSeeder extends Seeder
{
    public function run(): void
    {
        $modelScores = [
            'GPT-4o' => [
                'HR & communicatie' => 32, 'Technische documentatie' => 33, 'Code & reviewondersteuning' => 27,
                'Beleids- en teamtaken' => 30, 'Marketing & content' => 30, 'Onderzoek & analyse' => 32,
                'Klantenservice & support' => 31, 'Testen & validatie' => 30
            ],
            'Gemma (Ollama)' => [
                'HR & communicatie' => 32, 'Technische documentatie' => 23, 'Code & reviewondersteuning' => 22,
                'Beleids- en teamtaken' => 33, 'Marketing & content' => 27, 'Onderzoek & analyse' => 31,
                'Klantenservice & support' => 22, 'Testen & validatie' => 21
            ],
            'Llama3' => [
                'HR & communicatie' => 24, 'Technische documentatie' => 25, 'Code & reviewondersteuning' => 24,
                'Beleids- en teamtaken' => 23, 'Marketing & content' => 24, 'Onderzoek & analyse' => 25,
                'Klantenservice & support' => 22, 'Testen & validatie' => 23
            ],
            'LLaMa 3.3' => [
                'HR & communicatie' => 25, 'Technische documentatie' => 27, 'Code & reviewondersteuning' => 26,
                'Beleids- en teamtaken' => 24, 'Marketing & content' => 25, 'Onderzoek & analyse' => 26,
                'Klantenservice & support' => 24, 'Testen & validatie' => 24
            ],
            'Claude 3.5 Sonnet' => [
                'HR & communicatie' => 30, 'Technische documentatie' => 31, 'Code & reviewondersteuning' => 31,
                'Beleids- en teamtaken' => 32, 'Marketing & content' => 30, 'Onderzoek & analyse' => 30,
                'Klantenservice & support' => 31, 'Testen & validatie' => 30
            ],
            'Claude 3.5 Haiku' => [
                'HR & communicatie' => 28, 'Technische documentatie' => 30, 'Code & reviewondersteuning' => 29,
                'Beleids- en teamtaken' => 28, 'Marketing & content' => 29, 'Onderzoek & analyse' => 28,
                'Klantenservice & support' => 27, 'Testen & validatie' => 28
            ],
            'Claude 3.7 Sonnet' => [
                'HR & communicatie' => 31, 'Technische documentatie' => 35, 'Code & reviewondersteuning' => 33,
                'Beleids- en teamtaken' => 29, 'Marketing & content' => 29, 'Onderzoek & analyse' => 30,
                'Klantenservice & support' => 31, 'Testen & validatie' => 31
            ],
        ];

        foreach ($modelScores as $modelName => $useCases) {
            $model = AiModel::where('name', $modelName)->first();

            if (!$model) {
                continue;
            }

            foreach ($useCases as $useCaseName => $score) {
                $useCase = UseCase::where('name', $useCaseName)->first();

                if (!$useCase) {
                    continue;
                }

                ModelUseCaseScore::updateOrCreate(
                    [
                        'model_id' => $model->id,
                        'use_case_id' => $useCase->id,
                    ],
                    [
                        'score' => $score,
                    ]
                );
            }
        }
    }
}