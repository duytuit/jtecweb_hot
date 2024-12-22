<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\FrontPagesController;
use App\Http\Controllers\Frontend\PrintMakuController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
/*
|--------------------------------------------------------------------------
| Web Routes - Frontend routes.
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/js/lang', function () {
    if (App::environment('local'))
        Cache::forget('lang.js');
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');
        $files = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];
        foreach ($files as $file) {
            $name = basename($file, '.php');
            $strings[$name] = require $file;
        }
        return $strings;
    });
    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode($strings) . ';');
    exit();
})->name('assets.lang');

Auth::routes();
Route::get('qrcode', [App\Http\Controllers\QrcodeController::class, 'index']);
Route::get('qrcode/export', [App\Http\Controllers\QrcodeController::class], 'QrExportExcel')->name('QrExportExcel');
Route::post('qrcode/printfile', [App\Http\Controllers\QrcodeController::class, 'QrCodePrint'])->name('QrCodePrint');
Route::get('qrcode/printfile', [App\Http\Controllers\QrcodeController::class, 'GetDataPrint']);
Route::post('qrcode', [App\Http\Controllers\QrcodeController::class, 'importQrcodeData']);
Route::post('qrcode/generate', [App\Http\Controllers\QrcodeController::class, 'QrcodeGenerate']);

Route::post('qrcode/printfile2', [App\Http\Controllers\QrcodeController::class, 'QrCodePrint2'])->name('QrCodePrint2');
Route::get('qrcode/printfile2', [App\Http\Controllers\QrcodeController::class, 'GetDataPrint2']);

Route::get('question/import', [App\Http\Controllers\QuestionController::class, 'index']);
Route::post('question/import', [App\Http\Controllers\QuestionController::class, 'importExcelData']);

//print maku
Route::get('printMaku', [PrintMakuController::class, 'index'])->name('printMaku');

Route::get('/', [FrontPagesController::class, 'index'])->name('index');
Route::get('/exam', [FrontPagesController::class, 'exam'])->name('exam');

// New exam
Route::get('/examNew', [FrontPagesController::class, 'examNew'])->name('examNew');
Route::get('/viewDataAssemble', [FrontPagesController::class, 'viewDataAssemble'])->name('viewDataAssemble');
Route::get('/viewDataAssemblePdf', [FrontPagesController::class, 'viewDataAssemblePdf'])->name('viewDataAssemblePdf');
Route::post('/exam/detailReport', [FrontPagesController::class, 'detailReport'])->name('exam.detailReport');
Route::post('/exam/detailReport1', [FrontPagesController::class, 'detailReport1'])->name('exam.detailReport1');
Route::get('/maintenance', [FrontPagesController::class, 'maintenance'])->name('maintenance');
Route::get('/syncEmployee', [FrontPagesController::class, 'syncEmployee'])->name('syncEmployee');
Route::get('/updatePermision', [FrontPagesController::class, 'updatePermision'])->name('updatePermision');
Route::get('/syncDepartment', [FrontPagesController::class, 'syncDepartment'])->name('syncDepartment');
Route::get('/test', [FrontPagesController::class, 'test'])->name('test');
Route::get('/test1', [FrontPagesController::class, 'test1'])->name('test1');
Route::get('/check_device_realtime', [FrontPagesController::class, 'check_device_realtime'])->name('check_device_realtime');
Route::get('/check_device', [FrontPagesController::class, 'check_device'])->name('check_device');
Route::post('/check_device/store', [FrontPagesController::class, 'check_device_store'])->name('check_device_store');
Route::post('/assembleStore/store', [FrontPagesController::class, 'assembleStore'])->name('assembleStore');
Route::get('/test1', [FrontPagesController::class, 'test1'])->name('test1');
Route::get('/getKeysRedis', [FrontPagesController::class, 'getKeysRedis'])->name('getKeysRedis');
Route::post('/exam/store', [FrontPagesController::class, 'store'])->name('exam.store');
Route::post('/exam/storeNew', [FrontPagesController::class, 'storeNew'])->name('exam.storeNew');
Route::get('/clearCache', [FrontPagesController::class, 'clearCache'])->name('clearCache');
Route::get('/remote_lamp', [FrontPagesController::class, 'remote_lamp'])->name('remote_lamp');
Route::get('/asyncDir', [FrontPagesController::class, 'asyncDir'])->name('asyncDir');
Route::get('/getPCname', [FrontPagesController::class, 'getPCname'])->name('getPCname');
Route::get('/updateEmployeeProductionPlan', [FrontPagesController::class, 'updateEmployeeProductionPlan'])->name('updateEmployeeProductionPlan');
Route::get('/ImportEmp', function () {
    return view('frontend.pages.import_emp');
});
Route::get('/test-view', function () {
    // $data['edp'] = DB::connection('oracle_toa_set')->table('TDCJSIJI')->where('HINCD', 'like','3YY13E01754P4-01%')->where('SENBAN','E136')->first();
    // dd($data);
   // return view('frontend.pages.testView.test_view');
   $dfgdf = view('frontend.pages.testView.test_view')->render();
  dd($dfgdf);
});
Route::post('/ImportEmpPost', [FrontPagesController::class, 'ImportEmpPost'])->name('exam.ImportEmpPost');


Route::namespace('App\Http\Controllers\Frontend')->prefix('frontend')->name('frontend.')->group(function () {

    // route ProductionPlanController
    Route::prefix('productionPlans')->name('productionPlans.')->group(function () {
        Route::get('', 'ProductionPlanController@index')->name('index');
        Route::get('viewMobileProductionPlan', 'ProductionPlanController@viewMobileProductionPlan')->name('viewMobileProductionPlan');
        Route::get('viewProductionPlan', 'ProductionPlanController@viewProductionPlan')->name('viewProductionPlan');
        Route::get('asyncViewProductionPlan', 'ProductionPlanController@asyncViewProductionPlan')->name('asyncViewProductionPlan');
        Route::get('asyncProductionPlan', 'ProductionPlanController@asyncProductionPlan')->name('asyncProductionPlan');
        Route::get('asyncKTNQ', 'ProductionPlanController@asyncKTNQ')->name('asyncKTNQ');
        Route::get('asyncKTTM', 'ProductionPlanController@asyncKTTM')->name('asyncKTTM');
        Route::get('file_info', 'ProductionPlanController@file_info')->name('file_info');
        Route::post('action', 'ProductionPlanController@action')->name('action');
    });

});


