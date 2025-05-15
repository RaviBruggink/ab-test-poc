<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiModel;
use App\Models\UseCase;
use App\Models\Distribution;

class DistributionSeeder extends Seeder
{
    public function run(): void
    {
        $bots = [
            [
                'model' => 'GPT-4o',
                'use_case' => 'HR & communicatie',
                'bot_name' => 'HR Helper Bot',
                'description' => 'Ondersteunt HR-taken en interne communicatie.'
            ],
            [
                'model' => 'Llama3',
                'use_case' => 'Technische documentatie',
                'bot_name' => 'TechDoc Bot',
                'description' => 'Maakt technische handleidingen en documentatie.'
            ],
            [
                'model' => 'Claude 3.5 Sonnet',
                'use_case' => 'Klantenservice & support',
                'bot_name' => 'Support Assistant',
                'description' => 'Helpt klantenservice met snelle antwoorden en cases.'
            ],
        ];

        foreach ($bots as $bot) {
            $model = AiModel::where('name', $bot['model'])->first();
            $useCase = UseCase::where('name', $bot['use_case'])->first();

            if ($model && $useCase) {
                Distribution::updateOrCreate(
                    [
                        'model_id' => $model->id,
                        'use_case_id' => $useCase->id,
                    ],
                    [
                        'bot_name' => $bot['bot_name'],
                        'description' => $bot['description'],
                    ]
                );
            }
        }
    }
}