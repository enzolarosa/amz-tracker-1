<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Auth::routes(['register' => false]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/go/{shortUrl}', 'ShortUrlController@go')->name('short-url-go')->middleware('log-request:short-url-in');

Route::post('amztracker/telegram', function () {
    $update = Telegram::commandsHandler(true);

    return 'ok';
})->middleware('log-request:telegram-in');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

Route::get('/tracker', [HomeController::class, 'tracker'])->name('tracker');
