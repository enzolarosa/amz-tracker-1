<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['register' => false]);

Route::get('/', function () {
    return view('welcome');
});

Route::post('amztracker/telegram', function () {
    $update = Telegram\Bot\Laravel\Facades\Telegram::commandsHandler(true);
    return 'ok';
})->withoutMiddleware('log-request:web-in')->middleware('log-request:telegram-in');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard')->withoutMiddleware('log-request:web-in');


Route::get('/go/{shortUrl}', 'ShortUrlController@go')->name('short-url-go')
    ->withoutMiddleware('log-request:web-in')
    ->middleware('log-request:short-url-in');
