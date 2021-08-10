<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DailyActivityController;
use App\Http\Controllers\DailyReportController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'welcome']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/me', [AuthController::class, 'user']);
Route::get('login_simpeg', [AuthController::class, 'login_simpeg']);




Route::middleware('auth:api')->get('/current_user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => '/','middleware'=> 'auth'], function () {


    Route::get('/me/profile', [UserController::class, 'profile_user_aktif']);
    Route::get('/me/hirarki', [UserController::class, 'hirarki_user_aktif']);


    //========================================================================================================//
	//===========================             U    S    E     R           ====================================//
	//========================================================================================================//


    Route::get('user', [UserController::class, 'user_list']);
    Route::put('user_update', [UserController::class, 'user_update']);


    //========================================================================================================//
	//===========================         D A I L Y     R E P O R T       ====================================//
	//========================================================================================================//
    Route::post('daily_report', [DailyReportController::class, 'daily_report_create']);



    //========================================================================================================//
	//===========================     D A I L Y     A C T I V I T Y       ====================================//
	//========================================================================================================//

    //tes api siap
    Route::get('tes_siap', [DailyActivityController::class, 'tes_siap']);



    Route::get('daily_activity_create_confirm', [DailyActivityController::class, 'daily_activity_create_confirm']);


    Route::post('daily_activity', [DailyActivityController::class, 'daily_activity_store']);
    Route::get('daily_activity', [DailyActivityController::class, 'daily_activity_list']);
    Route::get('daily_activity/{id}', [DailyActivityController::class, 'daily_activity_show']);
    Route::put('daily_activity', [DailyActivityController::class, 'daily_activity_update']);


});
