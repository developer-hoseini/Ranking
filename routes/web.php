<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GamePageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use App\Livewire\Pages\Cups\ShowCup;
use App\Livewire\Pages\GameResults\QuickSubmit;
use App\Livewire\Pages\Games;
use App\Livewire\Pages\MgcCoin;
use App\Livewire\Pages\Profile\CompleteProfile;
use App\Livewire\Pages\Ranks;
use App\Livewire\Pages\Rules;
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
        Route::get('/login', [AuthController::class, 'showLogin'])->name('show-login');
        Route::Post('/login', [AuthController::class, 'login'])->name('login');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    /* Start Pages */
    Route::group([], function () {
        //home
        Route::get('/', [HomeController::class, 'index'])->name('home');

        //about
        Route::view('/about', 'pages.about')->name('about');

        //ranks
        Route::prefix('ranks')->name('ranks.')->group(function () {
            Route::get('/', Ranks::class)->name('index');
        });

        //cups
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

        //games
        Route::prefix('games')->name('games.')->group(function () {
            Route::get('/', Games::class)->name('index');
            Route::get('/{game}', [GameController::class, 'show'])->name('show');
            Route::get('/{id}/online', [GameController::class, 'showOnline'])->name('show.online');

            Route::middleware('auth')->group(function () {
                Route::get('/{id}/join', [GameController::class, 'join'])->middleware('auth')->name('join');
            });

            //game page
            Route::group(['as' => 'page.', 'prefix' => '/page', 'middleware' => ['auth', 'completeProfile']], function () {
                Route::get('/{game}/{opponent?}', [GamePageController::class, 'index'])->name('index');
                Route::post('/invite', [GamePageController::class, 'invite'])->name('invite');
            });
        });

        //profile
        Route::group(['prefix' => '/profile', 'as' => 'profile.'], function () {
            Route::middleware('auth')->group(function () {
                Route::get('/complete-profile', CompleteProfile::class)->name('complete-profile');
            });

            Route::post('/like', [ProfileController::class, 'like'])->name('like');
            Route::post('/report', [ProfileController::class, 'report'])->name('report');
            Route::post('/competitions', [ProfileController::class, 'competitions'])->name('competitions');
            Route::get('/team/certificates', [ProfileController::class, 'teamCertificates'])->name('team.certificates');
            Route::get('/{user}', [ProfileController::class, 'show'])->name('show')->where('contact', '[0-9]+');

        });

        //game-results
        Route::group(['prefix' => '/game-results', 'as' => 'game-results.'], function () {
            Route::middleware('auth')->group(function () {
                Route::get('/quick-submit', QuickSubmit::class)->name('quick-submit');
            });

        });

        //mgc-coin
        Route::group(['prefix' => '/mgc-coin', 'as' => 'mgc-coin.'], function () {
            Route::middleware('auth')->group(function () {
                Route::get('/', MgcCoin::class)->name('index');
            });

        });

        //rules
        Route::get('/rules', Rules::class)->name('rules');

    });
    /* End Pages */

    //test
    Route::get('/test', [TestController::class, 'index'])->name('test.index');

    //game

    Route::get('/tournament', [TestController::class, 'index'])->name('tournament.index');
    Route::get('/tournament/{competition}', [TestController::class, 'index'])->name('tournament.show');

    Route::get('/quick_submit', [TestController::class, 'index'])->name('quick_submit');
    // Route::get('/ranks', [TestController::class, 'index'])->middleware('auth')->name('ranks');
    Route::get('/events', [TestController::class, 'index'])->name('events');
    Route::get('/quick_submitted_list', [TestController::class, 'index'])->name('quick_submitted_list');
    Route::get('/team_ranks', [TestController::class, 'index'])->middleware('auth')->name('team_ranks');
    Route::view('/tutorial', [TestController::class, 'index'])->name('tutorial');
    Route::get('/set_qrcode', [TestController::class, 'index'])->name('set_qrcode');
    Route::get('/charge', [TestController::class, 'index'])->name('charge');
    Route::get('/my_tournament', [TestController::class, 'index'])->name('my_tournament.index');
    Route::get('/my_teams', [TestController::class, 'index'])->name('my_teams');
    Route::get('/chats', [TestController::class, 'index'])->name('chats');
    Route::get('/tickets', [TestController::class, 'index'])->name('tickets.index');
    Route::get('/teams/{team}', [TestController::class, 'index'])->name('teams.show');
    Route::get('/tickets', [TestController::class, 'index'])->name('tickets.index');

    Route::get('/prizes', [TestController::class, 'index'])->name('prizes');

    Route::view('/global_ranks', [TestController::class, 'index'])->name('global_ranks');

});
