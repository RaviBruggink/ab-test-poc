<?php

use App\Http\Controllers\ScoreController;

Route::get('/', [ScoreController::class, 'showABTest']);
Route::post('/vote', [ScoreController::class, 'vote']);
Route::get('/chart', [ScoreController::class, 'showChart']);