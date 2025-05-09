<?php

use App\Http\Controllers\ContestSummaryController;
use App\Http\Controllers\Dashboard\AssessmentController;
use App\Http\Controllers\Dashboard\ContestController;
use App\Http\Controllers\Dashboard\EvaluatorController;
use App\Http\Controllers\Dashboard\ExerciseController;
use App\Http\Controllers\Dashboard\SearchForUsersForEvaluatorsController;
use App\Http\Controllers\Dashboard\TeamController;
use App\Http\Controllers\Dashboard\TeamMemberController;
use App\Http\Controllers\Dashboard\UpdateStatusContestController;
use App\Http\Controllers\FavoriteContestController;
use App\Http\Controllers\Favorites\CheckFavoriteContestController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\CanEvaluate;
use App\Http\Middleware\CreatorOfTheContest;
use App\Http\Middleware\EvaluatorOfTheContest;
use App\Http\Middleware\IsContestEnded;
use App\Http\Middleware\IsContestPublished;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/contests/search', HomeController::class)->name('contests.search');

Route::get('/contests/{contest}/summary', ContestSummaryController::class)->name('contests.summary')->middleware(IsContestPublished::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());

    Route::apiResource('/contests', ContestController::class)->only(['index', 'store']);

    Route::apiResource('/favorites', FavoriteContestController::class)->only(['index', 'store']);
    Route::delete('/favorites/{favorite_contest}', [FavoriteContestController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/contests/{contest}/favorites', CheckFavoriteContestController::class)->name('contests.favorites');

    Route::middleware(CreatorOfTheContest::class)->group(function () {
        Route::apiResource('/contests', ContestController::class)->only(['update', 'destroy']);

        Route::post('/contests/{contest}/status', UpdateStatusContestController::class)->name('contests.status');
        Route::apiResource('/contests/{contest}/teams', TeamController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::apiResource('/contests/{contest}/exercises', ExerciseController::class)->only(['store', 'update', 'destroy']);

        Route::apiResource('/contests/{contest}/teams/{team}/members', TeamMemberController::class)->only(['index', 'store']);
        Route::get('/contests/{contest}/teams/{team}/members/{team_member}', [TeamMemberController::class, 'show'])->name('members.show');
        Route::put('/contests/{contest}/teams/{team}/members/{team_member}', [TeamMemberController::class, 'update'])->name('members.update');
        Route::delete('/contests/{contest}/teams/{team}/members/{team_member}', [TeamMemberController::class, 'destroy'])->name('members.destroy');
        Route::apiResource('/contests/{contest}/evaluators', EvaluatorController::class)->only(['index', 'store']);
        Route::delete('/contests/{contest}/evaluators/{contest_user}', [EvaluatorController::class, 'destroy'])->name('evaluators.destroy');
        Route::post('/contests/{contest}/evaluators/search', SearchForUsersForEvaluatorsController::class)->name('evaluators.search');
    });

    Route::middleware(EvaluatorOfTheContest::class)->group(function () {
        Route::apiResource('/contests', ContestController::class)->only(['show']);
        Route::apiResource('/contests/{contest}/teams', TeamController::class)->only(['show']);
        Route::apiResource('/contests/{contest}/exercises', ExerciseController::class)->only(['index', 'show']);
        Route::get('/contests/{contest}/teams/{team}/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
        Route::apiResource('/contests/{contest}/teams/{team}/assessments', AssessmentController::class)->only(['store', 'destroy'])->middleware(CanEvaluate::class);
    });
});

