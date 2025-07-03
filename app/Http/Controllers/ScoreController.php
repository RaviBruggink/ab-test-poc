<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiModel;
use App\Models\UseCase;
use App\Models\ModelUseCaseScore;

class ScoreController extends Controller
{
    public function showABTest()
    {
        $models = config('models.models');
        $useCases = config('models.use_cases');

        return view('ab-test', compact('models', 'useCases'));
    }
    
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
}
