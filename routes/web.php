<?php

use App\Http\Controllers\CupController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
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
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/test', [TestController::class, 'index'])->name('test.index');

    Route::get('/tournament', [TestController::class, 'index'])->name('tournament.index');
    Route::get('/tournament/{competition}', [TestController::class, 'index'])->name('tournament.show');

    Route::get('/quick_submit', [TestController::class, 'index'])->name('quick_submit');
    Route::get('/ranks', [TestController::class, 'index'])->middleware('auth')->name('ranks');
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

    Route::get('/profile/{user}', [TestController::class, 'index'])->name('profile');
    Route::get('/prizes', [TestController::class, 'index'])->name('prizes');

    Route::view('/global_ranks', [TestController::class, 'index'])->name('global_ranks');

    Route::get('/game/{game}', [GameController::class, 'show'])->name('game.show');

    Route::prefix('cups')->name('cup.')->group(function () {
        Route::get('/{cup}', [CupController::class, 'show'])->name('show');
    });

});
