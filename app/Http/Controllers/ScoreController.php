<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiModel;
use App\Models\UseCase;
use App\Models\ModelUseCaseScore;

class ScoreController extends Controller
{
    /**
     * Toon het AB-test formulier.
     *
     * @return \Illuminate\View\View
     */
    public function showABTest()
    {
        $models = config('models.models');
        $useCases = config('models.use_cases');

        return view('ab-test', compact('models', 'useCases'));
    }

    /**
     * Verwerk een stem van de gebruiker.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vote(Request $request)
{
    $validated = $request->validate([
        'use_case' => 'required|string',
        'model_a' => 'required|string',
        'model_b' => 'required|string',
        'chosen_model' => 'required|string',
        'distribution_id' => 'nullable|integer',
    ]);

    $model = \App\Models\AiModel::where('name', $validated['chosen_model'])->first();
    $useCase = \App\Models\UseCase::where('name', $validated['use_case'])->first();

    if ($model && $useCase) {
        $score = \App\Models\ModelUseCaseScore::firstOrCreate([
            'model_id' => $model->id,
            'use_case_id' => $useCase->id,
        ], ['score' => 0]);

        $score->increment('score');
    }

    if (!empty($validated['distribution_id'])) {
        // Hier kun je eventueel logging/stats doen
    }

    return redirect('/')
        ->withInput($validated)
        ->with('success', 'Stem opgeslagen!');
}


    /**
     * Toon het overzicht van de scores in een grafiek.
     *
     * @return \Illuminate\View\View
     */
    public function showChart()
    {
        $models = config('models.models');
        $useCases = config('models.use_cases');

        // Haal alle scores op inclusief relaties
        $allScores = ModelUseCaseScore::with(['model', 'useCase'])->get();

        $grouped = [];

        foreach ($models as $modelConfig) {
            $label = $modelConfig['label'];
            $model = AiModel::where('name', $label)->first();

            foreach ($useCases as $useCaseName) {
                $useCase = UseCase::where('name', $useCaseName)->first();

                if ($model && $useCase) {
                    // Vind score voor combinatie model/use case
                    $score = $allScores->firstWhere(function ($item) use ($model, $useCase) {
                        return $item->model_id === $model->id && $item->use_case_id === $useCase->id;
                    });

                    $grouped[$label][$useCaseName] = $score ? $score->score : 0;
                } else {
                    $grouped[$label][$useCaseName] = 0;
                }
            }
        }

        return view('chart', compact('models', 'useCases', 'grouped'));
    }
}
