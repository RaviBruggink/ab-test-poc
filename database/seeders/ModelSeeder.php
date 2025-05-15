<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiModel;

class ModelSeeder extends Seeder
{
    public function run(): void
    {
        $models = [
            'GPT-4o',
            'Gemma (Ollama)',
            'Llama3',
            'LLaMa 3.3',
            'Claude 3.5 Sonnet',
            'Claude 3.5 Haiku',
            'Claude 3.7 Sonnet',
        ];

        foreach ($models as $modelName) {
            AiModel::firstOrCreate(['name' => $modelName]);
        }
    }
}