<?php

use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LeaderboardController::class, 'index'])->name('leaderboard.index');
Route::post('/recalculate', [LeaderboardController::class, 'recalculate'])->name('leaderboard.recalculate');
