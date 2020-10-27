<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Guest\Index')->name('guest.index');

Route::group([
    'middleware' => 'auth'
], function () {
    Route::group([
        'prefix' => 'channels',
    ], function () {
        Route::get('/', 'Teams\Channels\Index')->name('channels.index');
        Route::get('/create', 'Teams\Channels\Create')->name('channels.create');
        Route::get('{channel}', 'Teams\Channels\Show')->name('channels.show');
    });

    Route::group([
        'prefix' => 'products',
    ], function () {
        Route::get('/', 'Product\Index')->name('products.index');
        Route::get('/create', 'Product\Create')->name('products.create');
        Route::get('/{product}', 'Product\Show')->name('products.show');
    });
});
