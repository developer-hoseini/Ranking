<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use App\Livewire\Pages\Cups\ShowCup;
use App\Livewire\Pages\Ranks;
use App\Livewire\Pages\Tournaments;
use App\Livewire\Pages\Tournaments\RegisterTournaments;
use App\Livewire\Pages\Tournaments\ShowTournaments;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => LaravelLocalization::setLocale()], function () {

    Route::prefix('auth')->name('auth.')->group(function () {
        Route::get('/login', [AuthController::class, 'login'])->name('login');
    });

    /* Start Pages */
    Route::name('')->group(function () {
        //home
        Route::get('/', [HomeController::class, 'index'])->name('home');

        //about
        Route::view('/about', 'pages.about')->name('about');

        //ranks
        Route::get('/ranks', Ranks::class)->name('ranks');

        Route::prefix('cups')->name('cup.')->group(function () {
            Route::get('/{id}', ShowCup::class)->name('show');
        });

        //tournaments
        Route::prefix('tournaments')->name('tournaments.')->group(function () {
            Route::get('/', Tournaments::class)->name('index');
            Route::get('/{id}', ShowTournaments::class)->name('show');
            /* TODO: complete this register */
            Route::get('/{id}/register', RegisterTournaments::class)->name('register');
        });

    });
    /* End Pages */

    //test
    Route::get('/test', [TestController::class, 'index'])->name('test.index');

    //game
    Route::get('/game/{game}', [GameController::class, 'show'])->name('game.show');

    //profile
    Route::group(['prefix' => '/profile', 'as' => 'profile.'], function () {
        Route::get('/{user}', [ProfileController::class, 'show'])->name('show');
        Route::post('/like', [ProfileController::class, 'like'])->name('like');
        Route::post('/report', [ProfileController::class, 'report'])->name('report');
        Route::post('/competitions', [ProfileController::class, 'competitions'])->name('competitions');
    });

    Route::get('/tournament', [TestController::class, 'index'])->name('tournament.index');
    Route::get('/tournament/{competition}', [TestController::class, 'index'])->name('tournament.show');

    Route::get('/quick_submit', [TestController::class, 'index'])->name('quick_submit');
    // Route::get('/ranks', [TestController::class, 'index'])->middleware('auth')->name('ranks');
    Route::get('/events', [TestController::class, 'index'])->name('events');
    Route::get('/quick_submitted_list', [TestController::class, 'index'])->name('quick_submitted_list');
    Route::get('/team_ranks', [TestController::class, 'index'])->middleware('auth')->name('team_ranks');
    Route::get('/games', [TestController::class, 'index'])->name('games');
    Route::view('/rules', [TestController::class, 'index'])->name('rules');
    Route::view('/tutorial', [TestController::class, 'index'])->name('tutorial');
    Route::get('/editprofile', [TestController::class, 'index'])->name('edit_profile');
    Route::get('/set_qrcode', [TestController::class, 'index'])->name('set_qrcode');
    Route::get('/logout', [TestController::class, 'index'])->name('logout');
    Route::get('/charge', [TestController::class, 'index'])->name('charge');
    Route::get('/my_tournament', [TestController::class, 'index'])->name('my_tournament.index');
    Route::get('/my_teams', [TestController::class, 'index'])->name('my_teams');
    Route::get('/chats', [TestController::class, 'index'])->name('chats');
    Route::get('/tickets', [TestController::class, 'index'])->name('tickets.index');
    Route::get('/teams/{team}', [TestController::class, 'index'])->name('teams.show');
    Route::get('/tickets', [TestController::class, 'index'])->name('tickets.index');
    Route::get('/gamepage/{game_id}/{opponent_id}', [TestController::class, 'index'])->name('select_opponent');

    Route::get('/prizes', [TestController::class, 'index'])->name('prizes');

    Route::view('/global_ranks', [TestController::class, 'index'])->name('global_ranks');

    Route::get('/profile_team_certificates', [TestController::class, 'index'])->name('profile_team_certificates');

});
