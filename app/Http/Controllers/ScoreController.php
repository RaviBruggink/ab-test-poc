<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelScore;

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
        ]);

        $score = \App\Models\ModelScore::firstOrCreate([
            'model_name' => $validated['chosen_model'],
            'use_case' => $validated['use_case'],
        ], ['score' => 0]);

        $score->increment('score');

        // Belangrijk: redirect WITH input!
        return redirect('/')
            ->withInput($validated)
            ->with('success', 'Stem opgeslagen!');
    }

    public function showChart()
    {
        $models = config('models.models');
        $useCases = config('models.use_cases');

        $scores = ModelScore::all();

        $grouped = [];
        foreach ($models as $model) {
            $label = $model['label'];
            foreach ($useCases as $useCase) {
                $grouped[$label][$useCase] = $scores
                    ->where('model_name', $label)
                    ->where('use_case', $useCase)
                    ->first()
                    ->score ?? 0;
            }
        }

        return view('chart', compact('models', 'useCases', 'grouped'));
    }
}

