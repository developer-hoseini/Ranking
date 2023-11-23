<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GamePageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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

    Route::prefix('auth')->group(function () {
        Route::name('auth.')->group(function () {
            Route::get('/login', [AuthController::class, 'showLogin'])->name('show-login');
            Route::Post('/login', [AuthController::class, 'login'])->name('login');
            Route::get('/register', [AuthController::class, 'showRegister'])->name('show-register');
            Route::Post('/register', [AuthController::class, 'register'])->name('register');
            Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
        });

        Route::middleware(['auth'])->group(function () {
            Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
            Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
            Route::post('/email/resend', [VerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.resend');
        });

        Route::get('/forgot-password', [ForgetPasswordController::class, 'showForgetPassword'])->middleware('guest')->name('password.request');
        Route::post('/forgot-password', [ForgetPasswordController::class, 'forgetPassword'])->middleware('guest')->name('password.email');

        Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'showResetPassword'])->middleware('guest')->name('password.reset');
        Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.update');

    });

    /* Start Pages */
    Route::group([], function () {
        //home
        Route::get('/', [HomeController::class, 'index'])->name('home');

        //about
        Route::view('/about', 'pages.about')->name('about');

        //ranks
        Route::prefix('ranks')->name('ranks.')->group(function () {
            Route::get('/team', \App\Livewire\Pages\Ranks\Team::class)->name('team');
            Route::get('/', \App\Livewire\Pages\Ranks::class)->name('index');
        });

        //cups
        Route::prefix('cups')->name('cup.')->group(function () {
            Route::get('/{id}', \App\Livewire\Pages\Cups\ShowCup::class)->name('show');
        });

        //tournaments
        Route::prefix('tournaments')->name('tournaments.')->group(function () {

            Route::middleware(['auth', 'completeProfile'])->group(function () {
                Route::get('/{id}/register', \App\Livewire\Pages\Tournaments\RegisterTournaments::class)->name('register');
                Route::get('/me', \App\Livewire\Pages\Tournaments\Me\Index::class)->name('me.index');
                Route::get('/me/create', \App\Livewire\Pages\Tournaments\Me\Index\Create::class)->name('me.create');
            });

            Route::get('/', \App\Livewire\Pages\Tournaments::class)->name('index');
            Route::get('/{id}', \App\Livewire\Pages\Tournaments\ShowTournaments::class)->name('show');

        });

        //games
        Route::prefix('games')->name('games.')->group(function () {
            Route::get('/', \App\Livewire\Pages\Games::class)->name('index');
            Route::get('/{game}', [GameController::class, 'show'])->name('show');
            Route::get('/{id}/online', [GameController::class, 'showOnline'])->name('show.online');

            /* TODO: uncomment verified middleware */
            Route::middleware(['auth'/* , 'verified' */])->group(function () {
                Route::get('/{id}/join', [GameController::class, 'join'])->middleware('auth')->name('join');
            });

            //game page
            /* TODO: uncomment verified middleware */

            Route::group(['as' => 'page.', 'prefix' => '/page', 'middleware' => ['auth'/* , 'verified' */, 'completeProfile']], function () {
                Route::get('/accept/{inviteId}', [GamePageController::class, 'accept'])->name('accept');
                Route::get('/reject/{inviteId}', [GamePageController::class, 'reject'])->name('reject');
                Route::get('/cancel/{inviteId}', [GamePageController::class, 'cancel'])->name('cancel');
                Route::post('/invite/{game}', [GamePageController::class, 'invite'])->name('invite');
                Route::get('/{game}/{opponent?}', [GamePageController::class, 'index'])->name('index');

            });
        });

        //profile
        Route::group(['prefix' => '/profile', 'as' => 'profile.'], function () {
            /* TODO: uncomment verified middleware */
            Route::middleware(['auth'/* , 'verified' */])->group(function () {
                Route::get('/complete-profile', \App\Livewire\Pages\Profile\CompleteProfile::class)->name('complete-profile');
            });

            Route::post('/like', [ProfileController::class, 'like'])->name('like');
            Route::post('/report', [ProfileController::class, 'report'])->name('report');
            Route::post('/tournamnets', [ProfileController::class, 'tournamnets'])->name('tournamnets');
            Route::get('/team/certificates', [ProfileController::class, 'teamCertificates'])->name('team.certificates');
            Route::get('/{user?}', [ProfileController::class, 'show'])->name('show')->where('user', '[0-9]+');
        });

        //game-results
        Route::group(['prefix' => '/game-results', 'as' => 'game-results.'], function () {

            /* TODO: uncomment verified middleware */
            Route::middleware(['auth'/* , 'verified' */, 'completeProfile'])->group(function () {
                Route::get('/', \App\Livewire\Pages\GameResult::class)->name('index');
                Route::get('/quick-submit', \App\Livewire\Pages\GameResults\QuickSubmit::class)->name('quick-submit');
            });

        });

        //mgc-coin
        Route::group(['prefix' => '/mgc-coin', 'as' => 'mgc-coin.'], function () {
            Route::middleware('auth')->group(function () {
                Route::get('/', \App\Livewire\Pages\MgcCoin::class)->name('index');
            });

        });

        //tickets
        Route::prefix('tickets')->middleware(['auth'])->name('tickets.')->group(function () {
            Route::get('/', \App\Livewire\Pages\Ticket::class)->name('index');
            Route::get('/{id}', \App\Livewire\Pages\Ticket\Show::class)->name('show');
        });

        //teams
        Route::prefix('teams')->name('teams.')->group(function () {
            Route::prefix('me')->name('me.')->middleware(['auth'])->group(function () {
                Route::get('/create', \App\Livewire\Pages\Teams\Create::class)->name('create');
                Route::get('/{id}/members', \App\Livewire\Pages\Teams\Me\Show\Members::class)->name('show.memebers');
                Route::get('/{id}/edit', \App\Livewire\Pages\Teams\Edit::class)->name('edit');
                Route::get('/', \App\Livewire\Pages\Teams\Me\Index::class)->name('index');
            });

            Route::get('/{id}', \App\Livewire\Pages\Teams\Show::class)->name('show');
        });

        //notifications
        Route::prefix('notifications')->middleware(['auth'])->name('notifications.')->group(function () {
            Route::get('/', \App\Livewire\Pages\Notification::class)->name('index');
        });

        //rules
        Route::get('/rules', \App\Livewire\Pages\Rules::class)->name('rules');

        //rules
        Route::get('/tutorial', \App\Livewire\Pages\Tutorial::class)->name('tutorial');

    });
    /* End Pages */

    //test
    Route::get('/test', [TestController::class, 'index'])->name('test.index');

    //game

    // Route::get('/ranks', [TestController::class, 'index'])->middleware('auth')->name('ranks');
    Route::get('/charge', [TestController::class, 'index'])->name('charge');
    Route::get('/chats', [TestController::class, 'index'])->name('chats');

    Route::get('/prizes', [TestController::class, 'index'])->name('prizes');
    Route::get('/chat/{user}', [TestController::class, 'index'])->name('chat.page');

});
