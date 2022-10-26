<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DailyActivityController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\SkpdController;
use App\Http\Controllers\RencanaKerjaTahunanController;
use App\Http\Controllers\TimKerjaController;
use App\Http\Controllers\PejabatController;
use App\Http\Controllers\RencanaKinerjaController;
use App\Http\Controllers\PeranHasilController;
use App\Http\Controllers\RencanaSKPController;
use App\Http\Controllers\PerjanjianKinerjaController;
use App\Http\Controllers\SasaranKinerjaController;
use App\Http\Controllers\SasaranKinerjaReviuController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\IndikatorKinerjaIndividuController;
use App\Http\Controllers\ManualIndikatorKinerjaController;
use App\Http\Controllers\ManajemenKinerjaController;
use App\Http\Controllers\MatrikPeranHasilController;
use App\Http\Controllers\PerilakuKerjaController;
use App\Http\Controllers\UserRoleController;



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
    Route::get('user', [UserController::class, 'UserList']);
    Route::get('user/{nip}', [UserController::class, 'user_detail']);
    Route::get('user/{nip}/hirarki', [UserController::class, 'user_hirarki']);

    Route::get('select_user', [UserController::class, 'select_user_list']);

    Route::put('user_update', [UserController::class, 'user_update']);

    Route::get('user_jabatan_list', [UserController::class, 'UserJabatanList']);
    Route::get('user_jabatan_detail', [UserController::class, 'UserJabatanDetail']);

    Route::get('user_all', [UserController::class, 'UserAllList']);

    Route::post('addPegawai', [UserController::class, 'addPegawai']);


     //========================================================================================================//
	//================================         U  S  E  R    R O L E       ====================================//
	//========================================================================================================//
    Route::post('update_role', [UserRoleController::class, 'update']);

    //========================================================================================================//
	//===========================           PEGAWAI FROM SIM ASN          ====================================//
	//========================================================================================================//

    Route::get('pegawai_detail', [PegawaiController::class, 'PegawaiDetail']);

     //========================================================================================================//
	//==================================           JABATAN        =============================================//
	//========================================================================================================//

    Route::get('jabatan_detail', [UserController::class, 'JabatanDetail']);



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
	//===========================                   PERIODE               ====================================//
	//========================================================================================================//
    Route::get('periode', [PeriodeController::class, 'PeriodeDetail']);
    Route::get('periode_list', [PeriodeController::class, 'PeriodeList']);




    //========================================================================================================//
	//===========================                  RENJA                   ====================================//
	//========================================================================================================//

    /* Route::get('create_renja', [RenjaController::class, 'create']);

    Route::get('renja', [RenjaController::class, 'Renja']);

    Route::get('personal_renja', [RenjaController::class, 'personal_renja_list']);

    Route::get('renja/{id}', [RenjaController::class, 'detail']);
    Route::post('renja', [RenjaController::class, 'store']);
    Route::delete('renja', [RenjaController::class, 'destroy']); */

    //========================================================================================================//
	//===========================           MANAJEMEN KINERJA                 ================================//
	//========================================================================================================//
    Route::get('manajemen_kinerja', [ManajemenKinerjaController::class, 'list']);
    Route::get('manajemen_kinerja_menu', [ManajemenKinerjaController::class, 'ManajemenKinerjaMenu']);


    //========================================================================================================//
	//===========================           PERJANJIAN KINERJA                ================================//
	//========================================================================================================//

    // =======     P  K      ===============//
    Route::get('perjanjian_kinerja_detail', [PerjanjianKinerjaController::class, 'PerjanjianKinerjaDetail']);
    Route::get('perjanjian_kinerja_id', [PerjanjianKinerjaController::class, 'PerjanjianKinerjaId']);
    Route::get('create_perjanjian_kinerja', [PerjanjianKinerjaController::class, 'PerjanjianKinerjaCreate']);
    Route::post('perjanjian_kinerja', [PerjanjianKinerjaController::class, 'PerjanjianKinerjaStore']);
    Route::get('perjanjian_kinerja', [PerjanjianKinerjaController::class, 'PerjanjianKinerjaList']);
    Route::put('submit_perjanjian_kinerja', [PerjanjianKinerjaController::class, 'PerjanjianKinerjaSubmit']);
    Route::delete('perjanjian_kinerja', [PerjanjianKinerjaController::class, 'PerjanjianKinerjaDestroy']);


    // ======= SASARAN STRATEGIS ===============//
    Route::get('sasaran_strategis_skpd', [PerjanjianKinerjaController::class, 'SasaranStrategis']);
    Route::get('sasaran_strategis_select_list', [PerjanjianKinerjaController::class, 'SasaranStrategisSelectList']);
    Route::get('sasaran_strategis', [PerjanjianKinerjaController::class, 'SasaranStrategisDetail']);
    Route::post('sasaran_strategis', [PerjanjianKinerjaController::class, 'SasaranStrategisStore']);
    Route::put('sasaran_strategis', [PerjanjianKinerjaController::class, 'SasaranStrategisUpdate']);
    Route::delete('sasaran_strategis', [PerjanjianKinerjaController::class, 'SasaranStrategisDestroy']);

    // ================         INDIKATOR SASARAN STRATEGIS / INDIKATOR KINERJA UTAMA        ===============//
    Route::get('indikator_sasaran_strategis', [PerjanjianKinerjaController::class, 'IndikatorSasaranStrategisDetail']);
    Route::post('indikator_sasaran_strategis', [PerjanjianKinerjaController::class, 'IndikatorSasaranStrategisStore']);
    Route::delete('indikator_sasaran_strategis', [PerjanjianKinerjaController::class, 'IndikatorSasaranStrategisDestroy']);
    Route::put('indikator_sasaran_strategis', [PerjanjianKinerjaController::class, 'IndikatorSasaranStrategisUpdate']);
    Route::get('indikator_kinerja_utama_select_list', [PerjanjianKinerjaController::class, 'IndikatorSasaranStrategisSelectList']);


    //========================================================================================================//
	//===========================             SASARAN KINERJA                 ================================//
	//========================================================================================================//
    Route::get('sasaran_kinerja_list', [SasaranKinerjaController::class, 'SasaranKinerjaList']);
    Route::get('sasaran_kinerja_id', [SasaranKinerjaController::class, 'SasaranKinerjaId']);

    Route::put('submit_sasaran_kinerja', [SasaranKinerjaController::class, 'SasaranKinerjaSubmit']);
    Route::get('sasaran_kinerja', [SasaranKinerjaController::class, 'SasaranKinerjaDetail']);
    Route::post('sasaran_kinerja', [SasaranKinerjaController::class, 'SasaranKinerjaStore']);
    Route::put('sasaran_kinerja', [SasaranKinerjaController::class, 'SasaranKinerjaUpdate']);
    Route::delete('sasaran_kinerja', [SasaranKinerjaController::class, 'SasaranKinerjaDestroy']);


    Route::get('sasaran_kinerja_bawahan_list', [SasaranKinerjaController::class, 'SasaranKinerjaBawahanList']);


    //========================================================================================================//
	//========================        SASARAN KINERJA  PEJABAT PENILAI         ===============================//
	//========================================================================================================//
    Route::post('sasaran_kinerja_pejabat_penilai', [SasaranKinerjaController::class, 'PejabatPenilaiStore']);
    Route::post('sasaran_kinerja_atasan_pejabat_penilai', [SasaranKinerjaController::class, 'AtasanPejabatPenilaiStore']);


    //========================================================================================================//
	//===========================        SASARAN KINERJA   REVIU              ================================//
	//========================================================================================================//
    Route::post('sasaran_kinerja_reviu', [SasaranKinerjaReviuController::class, 'Store']);




    //========================================================================================================//
	//======================           SASARAN KINERJA - RENCANA HAISL KERJA       ================================//
	//========================================================================================================//
    Route::get('sasaran_kinerja_rencana_kinerja', [RencanaKinerjaController::class, 'List']);
    Route::get('rencana_kinerja_select_list', [RencanaKinerjaController::class, 'SelectList']);
    Route::post('rencana_kinerja', [RencanaKinerjaController::class, 'Store']);
    Route::put('rencana_kinerja', [RencanaKinerjaController::class, 'Update']);
    Route::get('rencana_kinerja', [RencanaKinerjaController::class, 'Detail']);
    Route::delete('rencana_kinerja', [RencanaKinerjaController::class, 'Destroy']);

    //========================================================================================================//
	//======================           SASARAN KINERJA - PERILAKU KERJA       ================================//
	//========================================================================================================//



    Route::get('sasaran_kinerja_perilaku_kerja', [PerilakuKerjaController::class, 'List']);
    Route::post('sasaran_kinerja_perilaku_kerja', [PerilakuKerjaController::class, 'Store']);
    Route::put('sasaran_kinerja_perilaku_kerja', [PerilakuKerjaController::class, 'Update']);
    Route::delete('sasaran_kinerja_perilaku_kerja', [PerilakuKerjaController::class, 'Destroy']);

    Route::get('list_perwujudan_perilaku', [PerilakuKerjaController::class, 'ListPerwujudanPerilaku']);




    //========================================================================================================//
	//======================      SASARAN KINERJA - INDIKATOR KINERJA INDIVIDU ( IKI )   =====================//
	//========================================================================================================//
    Route::delete('indikator_kinerja_individu', [IndikatorKinerjaIndividuController::class, 'Destroy']);
    Route::post('indikator_kinerja_individu', [IndikatorKinerjaIndividuController::class, 'Store']);
    Route::get('indikator_kinerja_individu', [IndikatorKinerjaIndividuController::class, 'Detail']);
    Route::put('indikator_kinerja_individu', [IndikatorKinerjaIndividuController::class, 'Update']);





    //========================================================================================================//
	//========================       MANUAL INDIKATOR KINERJA INDIVIDU              ==========================//
	//========================================================================================================//

    Route::get('manual_indikator_kinerja', [ManualIndikatorKinerjaController::class, 'Detail']);
    Route::post('manual_indikator_kinerja', [ManualIndikatorKinerjaController::class, 'Store']);
    Route::put('manual_indikator_kinerja', [ManualIndikatorKinerjaController::class, 'Update']);


    //========================================================================================================//
	//====================      RENCANA HASIL KERJA PIMPINAN YANG DIINTERVENSI          ======================//
	//========================================================================================================//

    Route::get('rencana_hasil_kerja_pimpinan', [RencanaKinerjaController::class, 'RencanaHasilKerjaPimpinanList']);
    Route::post('rencana_hasil_kerja_pimpinan', [RencanaKinerjaController::class, 'RencanaHasilKerjaPimpinanStore']);



    //========================================================================================================//
	//========================                 MATRIK PERAN HASIL                   ==========================//
	//========================================================================================================//

    Route::get('matrik_peran_hasil', [MatrikPeranHasilController::class, 'matrikPeranHasilList']);

    Route::get('koordinator_list', [MatrikPeranHasilController::class, 'koordinatorList']);

    Route::delete('peran', [MatrikPeranHasilController::class, 'peranDestroy']);

    //PEGAWAI
    Route::delete('peran_pegawai', [MatrikPeranHasilController::class, 'peranPegawaiDestroy']);
    Route::post('peran_pegawai', [MatrikPeranHasilController::class, 'peranPegawaiStore']);

    Route::get('jabatan_child', [MatrikPeranHasilController::class, 'Children']);
    Route::get('list_jabatan', [MatrikPeranHasilController::class, 'ListJabatan']);
    Route::get('list_jabatan_atasan', [MatrikPeranHasilController::class, 'ListJabatanAtasan']);

    Route::post('jabatan', [MatrikPeranHasilController::class, 'jabatanStore']);

    Route::post('hasil', [MatrikPeranHasilController::class, 'hasilStore']);
    Route::get('hasil', [MatrikPeranHasilController::class, 'hasilDetail']);
    Route::put('hasil', [MatrikPeranHasilController::class, 'hasilUpdate']);
    Route::delete('hasil', [MatrikPeranHasilController::class, 'hasilDestroy']);

    Route::get('list_outcome_atasan', [MatrikPeranHasilController::class, 'ListOutcomeAtasan']);
    Route::get('list_pejabat_penilai_mph', [MatrikPeranHasilController::class, 'ListPejabatPenilai']);




    //========================================================================================================//
	//===========================         ADD PEJABAT MPH     TES              ==============================//
	//========================================================================================================//
    Route::post('pejabat_sasaran_kinerja', [SasaranKinerjaController::class, 'PejabatSasaranKinerjaStore']);






    //========================================================================================================//
	//===================               RENCANA KERJA TAHUNAN                   ==============================//
	//========================================================================================================//
    Route::get('rencana_kerja_tahunan_list', [RencanaKerjaTahunanController::class, 'list']);
    Route::get('rencana_kerja_tahunan_tree', [RencanaKerjaTahunanController::class, 'treeView']);

    Route::get('rencana_kerja_tahunan_level_0', [RencanaKerjaTahunanController::class, 'level_0']);
    Route::get('rencana_kerja_tahunan_child', [RencanaKerjaTahunanController::class, 'child']);

    //Route::get('create_rencana_kinerja', [RencanaKerjaTahunanController::class, 'create']);



     //========================================================================================================//
	//======================                      TIM KERJA                      ==============================//
	//========================================================================================================//

    Route::get('tim_kerja', [TimKerjaController::class, 'tim_kerja']);

    Route::get('tim_kerja_level_0', [TimKerjaController::class, 'tim_kerja_level_0']);
    Route::get('tim_kerja_self', [TimKerjaController::class, 'self']);
    Route::get('tim_kerja_child', [TimKerjaController::class, 'child']);

    Route::get('add_tim_kerja_referensi', [TimKerjaController::class, 'add_tim_kerja_referensi']);

    Route::post('add_tim_kerja', [TimKerjaController::class, 'store']);
    Route::delete('hapus_tim_kerja', [TimKerjaController::class, 'destroy']);


    Route::get('tim_kerja_rencana_kinerja_parent', [TimKerjaController::class, 'TimKerjaRencanaKinerjaParent']);


    //========================================================================================================//
	//======================                   TIM KERJA PEJABAT                 ==============================//
	//========================================================================================================//

    Route::get('renja_pejabat', [PejabatController::class, 'list']);
    Route::get('tim_kerja_pejabat', [PejabatController::class, 'tim_kerja_pejabat']);




    Route::post('add_pejabat_tim_kerja', [PejabatController::class, 'store']);
    Route::delete('hapus_pejabat_tim_kerja', [PejabatController::class, 'destroy']);





    //========================================================================================================//
	//==========================           MATRIKS     PERAN DAN HASIL           =============================//
	//========================================================================================================//
    //Route::get('matrik_peran_hasil', [PeranHasilController::class, 'matrik']);


    //========================================================================================================//
	//===========================               RENCANA   SKP                 ================================//
	//========================================================================================================//
    Route::get('create_rencana_skp', [RencanaSKPController::class, 'create']);
    Route::post('create_rencana_skp', [RencanaSKPController::class, 'store']);




});
