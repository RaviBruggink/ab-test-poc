<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\AiModel;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function index()
    {
        $distributions = Distribution::with(['model', 'useCase'])->get();

        return view('distributions.index', compact('distributions'));
    }

    public function show(Distribution $distribution)
    {
        return view('distributions.show', compact('distribution'));
    }

    public function startABTest(Distribution $distribution)
    {
        $otherModel = AiModel::where('id', '!=', $distribution->model_id)
            ->inRandomOrder()
            ->first();

        $models = [
            [
                'label' => $distribution->model->name,
                'color' => '#8B5CF6'
            ],
            [
                'label' => $otherModel->name,
                'color' => '#22C55E'
            ]
        ];

        $useCases = [
            $distribution->useCase->name
        ];

        return view('ab-test', compact('models', 'useCases', 'distribution'));
    }
}
