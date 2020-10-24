<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::group([
    'middleware' => 'log-request:web-in',
], function () {
    Auth::routes(['register' => false]);

    Route::get('/', function () {
        return view('welcome');
    });

    Route::post('amztracker/telegram', function () {
        $update = Telegram::commandsHandler(true);
        return 'ok';
    })->withoutMiddleware('log-request:web-in')->middleware('log-request:telegram-in');

    Route::get('/home', [HomeController::class, 'index'])
        ->name('home');

    Route::get('/dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard')
        ->withoutMiddleware('log-request:web-in');

    Route::get('/tracker', [HomeController::class, 'tracker'])
        ->name('tracker')
        ->withoutMiddleware('log-request:web-in');

    Route::get('/go/{shortUrl}', 'ShortUrlController@go')->name('short-url-go')
        ->withoutMiddleware('log-request:web-in')
        ->middleware('log-request:short-url-in');
});
