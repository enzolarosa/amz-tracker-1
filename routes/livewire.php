<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'channels',
], function () {
    Route::get('/', 'Teams\Channels\Index')->name('channels.index');
    Route::get('{channel}', 'Teams\Channels\Show')->name('channels.show');

});
