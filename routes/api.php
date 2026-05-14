<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardSetController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API works!']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout',  [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

// Cards 
Route::middleware('auth:sanctum')->prefix('cards')->group(function () {

    // Предустановленные карточки
    Route::middleware('role:global_admin')->group(function () {
        Route::get('/default',        [CardController::class, 'indexDefault']);
        Route::post('/default',       [CardController::class, 'storeDefault']);
        Route::put('/default/{id}',   [CardController::class, 'updateDefault']);
        Route::delete('/default/{id}',[CardController::class, 'destroyDefault']);
    });

    // Кастомные карточки
    Route::get('/',        [CardController::class, 'index']);
    Route::get('/{id}',    [CardController::class, 'show']);
    Route::post('/',       [CardController::class, 'store']);
    Route::put('/{id}',    [CardController::class, 'update']);
    Route::delete('/{id}', [CardController::class, 'destroy']);
});

// Card sets 
Route::middleware('auth:sanctum')->prefix('card-sets')->group(function () {

    // Просмотр
    Route::get('/',      [CardSetController::class, 'index']);
    Route::get('/{id}',  [CardSetController::class, 'show']);

    // Создание/редактирование/удаление наборов
    Route::middleware('role:center_admin,global_admin')->group(function () {
        Route::post('/',                          [CardSetController::class, 'store']);
        Route::put('/{id}',                       [CardSetController::class, 'update']);
        Route::delete('/{id}',                    [CardSetController::class, 'destroy']);
        Route::post('/{id}/cards',                [CardSetController::class, 'addCard']);
        Route::delete('/{id}/cards/{cardId}',     [CardSetController::class, 'removeCard']);
    });

    // Назначение набора ребёнку
    Route::post('/{id}/assign',                   [CardSetController::class, 'assignToChild']);
    Route::delete('/{id}/assign/{childId}',       [CardSetController::class, 'detachFromChild']);
});
