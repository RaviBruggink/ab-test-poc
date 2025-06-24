<?php

use App\Http\Controllers\ScoreController;
use App\Http\Controllers\DistributionController;
//! This import was missing for the routes idk if this also was a issue on your end?
use Illuminate\Support\Facades\Route;

Route::get('/distributions', [DistributionController::class, 'index'])->name('distributions.index');
Route::get('/distributions/{distribution}', [DistributionController::class, 'show'])->name('distributions.show');
Route::get('/distributions/{distribution}/ab-test', [DistributionController::class, 'startABTest'])->name('distributions.abTest');



Route::get('/', [ScoreController::class, 'showABTest']);
Route::post('/vote', [ScoreController::class, 'vote']);
//! Why do you have the logic for the chart in the ScoreController? It would be better suited in a dedicated ChartController.
Route::get('/chart', [ScoreController::class, 'showChart']);

