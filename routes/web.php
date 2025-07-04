<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\DistributionController;
use Illuminate\Support\Facades\Route;

Route::get('/distributions', [DistributionController::class, 'index'])->name('distributions.index');
Route::get('/distributions/{distribution}', [DistributionController::class, 'show'])->name('distributions.show');
Route::get('/distributions/{distribution}/ab-test', [DistributionController::class, 'startABTest'])->name('distributions.abTest');

Route::get('/', [ScoreController::class, 'showABTest']);
Route::post('/vote', [ScoreController::class, 'vote']);
Route::get('/chart', [ChartController::class, 'showChart']);

