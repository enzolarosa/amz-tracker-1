<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;


Route::get('/go/{shortUrl}', [ShortUrlController::class, 'go'])->name('short-url-go')->middleware('log-request:short-url-in');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

/**
 * Webhook Section
 */
Route::post('amztracker/telegram', [TelegramController::class, 'handleWebhook'])->middleware('log-request:telegram-in');
Route::post('stripe/webhook', [WebhookController::class, 'handleWebhook'])->middleware('log-request:stipe-in');
