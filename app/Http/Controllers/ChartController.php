<?php

namespace App\Http\Controllers;

use App\Models\AiModel;
use App\Models\UseCase;
use App\Models\ModelUseCaseScore;

class ChartController extends Controller
{
    public function showChart()
    {
        $models = config('models.models');
        $useCases = config('models.use_cases');

        $modelLabels = collect($models)->pluck('label');
        $useCaseNames = $useCases;

        $modelRows = AiModel::whereIn('name', $modelLabels)->get()->keyBy('name');
        $useCaseRows = UseCase::whereIn('name', $useCaseNames)->get()->keyBy('name');

        $allScores = ModelUseCaseScore::with(['model', 'useCase'])->get();

        $grouped = [];
        foreach ($modelLabels as $label) {
            foreach ($useCaseNames as $useCaseName) {
                $model = $modelRows[$label] ?? null;
                $useCase = $useCaseRows[$useCaseName] ?? null;

                $score = $model && $useCase
                    ? $allScores->firstWhere(fn($s) =>
                        $s->model_id === $model->id && $s->use_case_id === $useCase->id)
                    : null;
                $grouped[$label][$useCaseName] = $score?->score ?? 0;
            }
        }

        // pass values as the chart expects
        return view('chart', [
            'models' => $models,
            'useCases' => $useCases,
            'grouped' => $grouped,
        ]);
    }
}
