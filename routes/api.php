<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);

Route::group(['middleware'=>'auth:api'], function(){
    //User

    Route::post('/updateUser{id}', [\App\Http\Controllers\UserController::class, 'update']);
    Route::post('/deleteUser{id}', [\App\Http\Controllers\UserController::class, 'destroy']);
    Route::get('/getUser{id}', [\App\Http\Controllers\UserController::class, 'show']);
    Route::get('/getAllUser', [\App\Http\Controllers\UserController::class, 'index']);

    //Category
    Route::post('/addCategory', [\App\Http\Controllers\CategoryController::class, 'store']);
    Route::post('/updateCategory{id}', [\App\Http\Controllers\CategoryController::class, 'update']);
    Route::post('/deleteCategory{id}', [\App\Http\Controllers\CategoryController::class, 'destroy']);

    //News
    Route::post('/addNews', [\App\Http\Controllers\NewsController::class, 'store']);
    Route::post('/updateNew{id}', [\App\Http\Controllers\NewsController::class, 'update']);
    Route::post('/deleteNews{id}', [\App\Http\Controllers\NewsController::class, 'destroy']);

});

//User
Route::post('/addUser', [\App\Http\Controllers\UserController::class, 'store']);

//Category

Route::get('/getCategories', [\App\Http\Controllers\CategoryController::class, 'index']);

//News

Route::get('/getNews', [\App\Http\Controllers\NewsController::class, 'index']);
Route::get('/getNewsByTitle', [\App\Http\Controllers\NewsController::class, 'getNewsByTitle']);
Route::get('/getNewsByCategory', [\App\Http\Controllers\NewsController::class, 'getNewsByCategory']);
Route::get('/getNewsByDate', [\App\Http\Controllers\NewsController::class, 'getNewsByDate']);
Route::get('/getNewsSearch', [\App\Http\Controllers\NewsController::class, 'getNewsSearch']);







