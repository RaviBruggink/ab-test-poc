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

        $useCase = UseCase::where('name', $validated['use_case'])->first();

        $modelWinner = AiModel::where('name', $validated['chosen_model'])->first();
        $modelLoserName = $validated['model_a'] === $validated['chosen_model']
            ? $validated['model_b']
            : $validated['model_a'];
        $modelLoser = AiModel::where('name', $modelLoserName)->first();

        if ($modelWinner && $modelLoser && $useCase) {
            // Winnaar krijgt +1
            $winnerScore = ModelUseCaseScore::firstOrCreate([
                'model_id' => $modelWinner->id,
                'use_case_id' => $useCase->id,
            ], ['score' => 0]);
            $winnerScore->increment('score');

            // Verliezer krijgt -1
            $loserScore = ModelUseCaseScore::firstOrCreate([
                'model_id' => $modelLoser->id,
                'use_case_id' => $useCase->id,
            ], ['score' => 0]);
            $loserScore->decrement('score');
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
        // Solution
        // AiModel::where('name', config('models.models.*.label'))->get();
        // UseCase::where('name', config('models.use_cases.*'))->get();

        //! Here you get all the models within the config file
        $models = config('models.models');
        $useCases = config('models.use_cases');

        // Haal alle scores op inclusief relaties
        $allScores = ModelUseCaseScore::with(['model', 'useCase'])->get();

        $grouped = [];

        //! Then you loop through all the models
        foreach ($models as $modelConfig) {

            //! You get the label and then get the AiModel by that label
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
