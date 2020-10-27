<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Guest\Index')->name('guest.index');

Route::group([
    'prefix' => 'channels',
    'name' => 'teams.'
], function () {
    Route::get('/', 'Teams\Channels\Index')->name('channels.index');
    Route::get('/create', 'Teams\Channels\Create')->name('channels.create');
    Route::get('{channel}', 'Teams\Channels\Show')->name('channels.show');
});

Route::group([
    'prefix' => 'products',
    'name' => 'products.'
], function () {
    Route::get('/', 'Product\Index')->name('index');
    Route::get('/create', 'Product\Create')->name('create');
    Route::get('/{product}', 'Product\Show')->name('show');
});
