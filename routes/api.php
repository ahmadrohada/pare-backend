<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DailyActivityController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\SkpdController;
use App\Http\Controllers\RenjaController;
use App\Http\Controllers\RencanaKerjaTahunanController;
use App\Http\Controllers\TimKerjaController;
use App\Http\Controllers\PejabatController;


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

    //============= persetasi sinkronisasi data simpeg ====================//
    Route::get('/sync_percentage', [UserController::class, 'sync_percentage']);


    Route::get('/me/profile', [UserController::class, 'profile_user_aktif']);
    Route::get('/me/hirarki', [UserController::class, 'hirarki_user_aktif']);


    //========================================================================================================//
	//===========================             U    S    E     R           ====================================//
	//========================================================================================================//
    Route::get('user', [UserController::class, 'user_list']);
    Route::get('user/{nip}', [UserController::class, 'user_detail']);


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


    //========================================================================================================//
	//===========================                  SKPD                   ====================================//
	//========================================================================================================//
    Route::get('skpd', [SkpdController::class, 'list']);
    Route::get('skpd/{id}', [SkpdController::class, 'detail']);


    //========================================================================================================//
	//===========================                  RENJA                   ====================================//
	//========================================================================================================//
    Route::get('renja', [RenjaController::class, 'list']);
    Route::get('renja/{id}', [RenjaController::class, 'detail']);

    //========================================================================================================//
	//======================                  RENCANA KINERJA                   ==============================//
	//========================================================================================================//
    Route::get('rencana_kerja_tahunan_list', [RencanaKerjaTahunanController::class, 'list']);
    Route::get('rencana_kerja_tahunan_tree', [RencanaKerjaTahunanController::class, 'treeView']);

    Route::get('rencana_kerja_tahunan_level_0', [RencanaKerjaTahunanController::class, 'level_0']);
    Route::get('rencana_kerja_tahunan_child', [RencanaKerjaTahunanController::class, 'child']);


     //========================================================================================================//
	//======================                      TIM KERJA                      ==============================//
	//========================================================================================================//

    Route::get('tim_kerja', [TimKerjaController::class, 'tim_kerja']);

    Route::get('tim_kerja_level_0', [TimKerjaController::class, 'tim_kerja_level_0']);
    Route::get('tim_kerja_child', [TimKerjaController::class, 'child']);

      //========================================================================================================//
	//======================                   PEJABAT                   ==============================//
	//========================================================================================================//

    Route::get('renja_pejabat', [PejabatController::class, 'list']);


});
