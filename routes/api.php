<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\RestaurantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/types', [TypeController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/restaurants/bestSeller', [RestaurantController::class, 'bestRestaurants']);
Route::get('/restaurants/newInTown', [RestaurantController::class, 'newInTown']);
Route::put('/update/{dish}', [DishController::class, 'update']);
Route::delete('/delete/{dish}', [DishController::class, 'delete']);
Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);
Route::get('/restaurants/search/advance/{query}', [RestaurantController::class, 'advancedSearch']);
Route::get('/orders/{id}', [OrderController::class, 'show']);