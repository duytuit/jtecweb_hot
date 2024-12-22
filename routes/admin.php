<?php

use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\Auth\ResetPasswordController;
use App\Http\Controllers\Backend\BlogsController;
use App\Http\Controllers\Backend\CacheController;
use App\Http\Controllers\Backend\ContactsController;
use App\Http\Controllers\Backend\DashboardsController;
use App\Http\Controllers\Backend\LanguagesController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Admin Panel Route List
|
 */

Route::get('/', [DashboardsController::class, 'index'])->name('index');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout/submit', [LoginController::class, 'logout'])->name('logout');

// Reset Password Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

/**
 * Admin Management Routes
 */
Route::group(['prefix' => ''], function () {
    Route::resource('admins', AdminsController::class);
    Route::get('admins/trashed/view', [AdminsController::class, 'trashed'])->name('admins.trashed');
    Route::get('profile/edit', [AdminsController::class, 'editProfile'])->name('admins.profile.edit');
    Route::put('profile/update', [AdminsController::class, 'updateProfile'])->name('admins.profile.update');
    Route::delete('admins/trashed/destroy/{id}', [AdminsController::class, 'destroyTrash'])->name('admins.trashed.destroy');
    Route::put('admins/trashed/revert/{id}', [AdminsController::class, 'revertFromTrash'])->name('admins.trashed.revert');
});

/**
 * Role & Permission Management Routes
 */
Route::group(['prefix' => ''], function () {
    Route::resource('roles', RolesController::class);
});

/**
 * Blog Management Routes
 */
Route::group(['prefix' => ''], function () {
    Route::resource('blogs', BlogsController::class);
    Route::get('blogs/trashed/view', [BlogsController::class, 'trashed'])->name('blogs.trashed');
    Route::delete('blogs/trashed/destroy/{id}', [BlogsController::class, 'destroyTrash'])->name('blogs.trashed.destroy');
    Route::put('blogs/trashed/revert/{id}', [BlogsController::class, 'revertFromTrash'])->name('blogs.trashed.revert');
});

Route::namespace('App\Http\Controllers\Backend')->group(function () {

    Route::post('/upload', 'HomeController@upload');
    /**
     * Exam Management Routes
     */
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('', 'ExamController@index')->name('index');
        Route::get('/audit', 'ExamController@index1')->name('audit');
        Route::get('create', 'ExamController@create')->name('create');
        Route::get('show/{id}', 'ExamController@show')->name('show');
        Route::get('exportExcel', 'ExamController@exportExcel')->name('exportExcel');
        Route::get('AuditExport', 'ExamController@exportExcelAudit')->name('exportExcelAudit');
        Route::get('reportFailAnswer', 'ExamController@reportFailAnswer')->name('reportFailAnswer');
        Route::post('action', 'ExamController@action')->name('action');
        Route::delete('trashed/destroy/{id}', 'ExamController@destroyTrash')->name('trashed.destroy');
        Route::get('trashed/view', 'ExamController@trashed')->name('trashed');
        Route::put('trashed/revert/{id}', 'ExamController@revertFromTrash')->name('trashed.revert');
    });
    /**
     * Department Management Routes
     */
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('', 'DepartmentController@index')->name('index');
        Route::get('create', 'DepartmentController@create')->name('create');
        Route::get('edit/{id}', 'DepartmentController@edit')->name('edit');
        Route::get('ajaxGetSelectCode', 'DepartmentController@ajaxGetSelectCode')->name('ajaxGetSelectCode');
        Route::post('changePositionTitle', 'DepartmentController@changePositionTitle')->name('changePositionTitle');
        Route::post('changePermissions', 'DepartmentController@changePermissions')->name('changePermissions');
        Route::post('import', 'DepartmentController@importExcelData')->name('importExcelData');
        Route::get('exportExcel', 'DepartmentController@exportExcel')->name('exportExcel');
        Route::post('store', 'DepartmentController@store')->name('store');
        Route::post('update/{id}', 'DepartmentController@update')->name('update');
        Route::post('action', 'DepartmentController@action')->name('action');
        Route::post('addEmployeeIntoDepartment', 'DepartmentController@addEmployeeIntoDepartment')->name('addEmployeeIntoDepartment');
        Route::put('trashed/revert/{id}', 'DepartmentController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy/{id}', 'DepartmentController@destroyTrash')->name('trashed.destroy');
        Route::post('destroyEmployeeDepartments', 'DepartmentController@destroyEmployeeDepartments')->name('destroyEmployeeDepartments');
    });

      /**
     * assets Management Routes
     */
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('', 'AssetController@index')->name('index');
        Route::get('create', 'AssetController@create')->name('create');
        Route::get('edit/{id}', 'AssetController@edit')->name('edit');
        Route::post('store', 'AssetController@store')->name('store');
        Route::post('update/{id}', 'AssetController@update')->name('update');
        Route::post('action', 'AssetController@action')->name('action');
        Route::put('trashed/revert/{id}', 'AssetController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy/{id}', 'AssetController@destroyTrash')->name('trashed.destroy');
    });
      /**
     * Request Form Management Routes
     */
    Route::prefix('requestForms')->name('requestForms.')->group(function () {
        Route::get('', 'RequestFormController@index')->name('index');
        Route::get('create', 'RequestFormController@create')->name('create');
        Route::get('edit/{id}', 'RequestFormController@edit')->name('edit');
        Route::post('store', 'RequestFormController@store')->name('store');
        Route::post('update/{id}', 'RequestFormController@update')->name('update');
        Route::post('action', 'RequestFormController@action')->name('action');
        Route::put('trashed/revert/{id}', 'RequestFormController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy/{id}', 'RequestFormController@destroyTrash')->name('trashed.destroy');
    });

          /**
     * Request Form Management Routes
     */
    Route::prefix('cutedp')->name('cutedps.')->group(function () {
        Route::get('', 'CutEdpController@index')->name('index');
        Route::get('create', 'CutEdpController@create')->name('create');
        Route::get('edit/{id}', 'CutEdpController@edit')->name('edit');
        Route::get('detail/{id}', 'CutEdpController@detail')->name('detail');
        Route::post('store', 'CutEdpController@store')->name('store');
        Route::post('update/{id}', 'CutEdpController@update')->name('update');
        Route::post('action', 'CutEdpController@action')->name('action');
        Route::get('exportExcel', 'CutEdpController@exportExcel')->name('exportExcel');
        Route::get('createPrintPdf', 'CutEdpController@createPrintPdf')->name('createPrintPdf');
        Route::put('trashed/revert/{id}', 'CutEdpController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy/{id}', 'CutEdpController@destroyTrash')->name('trashed.destroy');
        Route::get('ajaxGetSelectByHINCD', 'CutEdpController@ajaxGetSelectByHINCD')->name('ajaxGetSelectByHINCD');
        Route::get('ajaxGetSelectBySENBAN', 'CutEdpController@ajaxGetSelectBySENBAN')->name('ajaxGetSelectBySENBAN');
        Route::get('ajaxGetSelectByLotNo', 'CutEdpController@ajaxGetSelectByLotNo')->name('ajaxGetSelectByLotNo');
        Route::get('ajaxGetSelectBySENSYU', 'CutEdpController@ajaxGetSelectBySENSYU')->name('ajaxGetSelectBySENSYU');
    });

    /**
     * Acivity Management Routes
     */
    Route::prefix('activitys')->name('activitys.')->group(function () {
        Route::get('', 'ActivityController@index')->name('index');
        Route::get('create', 'ActivityController@create')->name('create');
        Route::get('edit/{id}', 'ActivityController@edit')->name('edit');
        Route::get('exportExcel', 'ActivityController@exportExcel')->name('exportExcel');
        Route::post('store', 'ActivityController@store')->name('store');
        Route::post('update', 'ActivityController@update')->name('update');
        Route::post('action', 'ActivityController@action')->name('action');
        Route::get('trashed/destroy/{id}', 'ActivityController@destroyTrash')->name('trashed.destroy');
        Route::get('trashed/view', 'ActivityController@trashed')->name('trashed');
        Route::put('trashed/revert/{id}', 'ActivityController@revertFromTrash')->name('trashed.revert');
    });


       /**
     * Upload Data Management Routes
     */
    Route::prefix('uploadData')->name('uploadDatas.')->group(function () {
        Route::get('', 'UploadDataController@index')->name('index');
        Route::get('restartWebPdf', 'UploadDataController@restartWebPdf')->name('restartWebPdf');
        Route::get('create', 'UploadDataController@create')->name('create');
        Route::get('edit/{id}', 'UploadDataController@edit')->name('edit');
        Route::post('store', 'UploadDataController@store')->name('store');
        Route::post('store_new', 'UploadDataController@store_new')->name('store_new');
        Route::post('update/{id}', 'UploadDataController@update')->name('update');
        Route::post('action', 'UploadDataController@action')->name('action');
        Route::put('trashed/revert/{id}', 'UploadDataController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy/{id}', 'UploadDataController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Assemble Management Routes
     */
    Route::prefix('assembles')->name('assembles.')->group(function () {
        Route::get('', 'AssembleController@index')->name('index');
        Route::get('create', 'AssembleController@create')->name('create');
        Route::get('edit/{id}', 'AssembleController@edit')->name('edit');
        Route::post('store', 'AssembleController@store')->name('store');
        Route::post('update/{id}', 'AssembleController@update')->name('update');
        Route::post('action', 'AssembleController@action')->name('action');
        Route::put('trashed/revert/{id}', 'AssembleController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy/{id}', 'AssembleController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Campaign Management Routes
     */
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('', 'CampaignController@index')->name('index');
        Route::get('create', 'CampaignController@create')->name('create');
        Route::get('edit/{id}', 'CampaignController@edit')->name('edit');
        Route::get('exportExcel', 'CampaignController@exportExcel')->name('exportExcel');
        Route::post('store', 'CampaignController@store')->name('store');
        Route::post('update', 'CampaignController@update')->name('update');
        Route::post('action', 'CampaignController@action')->name('action');
        Route::put('trashed/revert/{id}', 'CampaignController@revertFromTrash')->name('trashed.revert');
        Route::delete('trashed/destroy/{id}', 'CampaignController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * CampaignDetails Management Routes
     */
    Route::prefix('campaignDetails')->name('campaignDetails.')->group(function () {
        Route::get('', 'CampaignDetailController@index')->name('index');
        Route::get('create', 'CampaignDetailController@create')->name('create');
        Route::get('edit/{id}', 'CampaignDetailController@edit')->name('edit');
        Route::get('exportExcel', 'CampaignDetailController@exportExcel')->name('exportExcel');
        Route::post('store', 'CampaignDetailController@store')->name('store');
        Route::post('update', 'CampaignDetailController@update')->name('update');
        Route::post('action', 'CampaignDetailController@action')->name('action');
        Route::put('trashed/revert/{id}', 'CampaignDetailController@revertFromTrash')->name('trashed.revert');
        Route::delete('trashed/destroy/{id}', 'CampaignDetailController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Comment Management Routes
     */
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('', 'CommentController@index')->name('index');
        Route::get('create', 'CommentController@create')->name('create');
        Route::get('edit/{id}', 'CommentController@edit')->name('edit');
        Route::get('exportExcel', 'CommentController@exportExcel')->name('exportExcel');
        Route::post('store', 'CommentController@store')->name('store');
        Route::post('update', 'CommentController@update')->name('update');
        Route::post('action', 'CommentController@action')->name('action');
        Route::put('trashed/revert/{id}', 'CommentController@revertFromTrash')->name('trashed.revert');
        Route::delete('trashed/destroy/{id}', 'CommentController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Cronjob Management Routes
     */
    Route::prefix('cronjobs')->name('cronjobs.')->group(function () {
        Route::get('', 'CronjobController@index')->name('index');
        Route::get('create', 'CronjobController@create')->name('create');
        Route::get('edit/{id}', 'CronjobController@edit')->name('edit');
        Route::get('exportExcel', 'CronjobController@exportExcel')->name('exportExcel');
        Route::post('store', 'CronjobController@store')->name('store');
        Route::post('update', 'CronjobController@update')->name('update');
        Route::post('action', 'CronjobController@action')->name('action');
        Route::put('trashed/revert/{id}', 'CronjobController@revertFromTrash')->name('trashed.revert');
        Route::delete('trashed/destroy/{id}', 'CronjobController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Employee Management Routes
     */
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('', 'EmployeeController@index')->name('index');
        Route::get('create', 'EmployeeController@create')->name('create');
        Route::get('edit/{id}', 'EmployeeController@edit')->name('edit');
        Route::get('ajaxGetSelectByName', 'EmployeeController@ajaxGetSelectByName')->name('ajaxGetSelectByName');
        Route::get('exportExcel', 'EmployeeController@exportExcel')->name('exportExcel');
        Route::get('importExcelData', 'EmployeeController@importExcelData')->name('importExcelData');
        Route::post('store', 'EmployeeController@store')->name('store');
        Route::post('update/{id}', 'EmployeeController@update')->name('update');
        Route::post('action', 'EmployeeController@action')->name('action');
        Route::put('trashed/revert/{id}', 'EmployeeController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy/{id}', 'EmployeeController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Employee Department Management Routes
     */
    Route::prefix('employeeDepartments')->name('employeeDepartments.')->group(function () {
        Route::get('', 'EmployeeDepartmentController@index')->name('index');
        Route::get('create', 'EmployeeDepartmentController@create')->name('create');
        Route::get('edit/{id}', 'EmployeeDepartmentController@edit')->name('edit');
        Route::get('exportExcel', 'EmployeeDepartmentController@exportExcel')->name('exportExcel');
        Route::post('store', 'EmployeeDepartmentController@store')->name('store');
        Route::post('update', 'EmployeeDepartmentController@update')->name('update');
        Route::post('action', 'EmployeeDepartmentController@action')->name('action');
        Route::put('trashed/revert/{id}', 'EmployeeDepartmentController@revertFromTrash')->name('trashed.revert');
        Route::delete('trashed/destroy/{id}', 'EmployeeDepartmentController@destroyTrash')->name('trashed.destroy');
    });

      /**
     * ProductionPlanController Routes
     */
    Route::prefix('productionPlans')->name('productionPlans.')->group(function () {
        Route::get('', 'ProductionPlanController@index')->name('index');
        Route::get('create', 'ProductionPlanController@create')->name('create');
        Route::get('asyncProductionPlan', 'ProductionPlanController@asyncProductionPlan')->name('asyncProductionPlan');
        Route::get('asyncKTNQ', 'ProductionPlanController@asyncKTNQ')->name('asyncKTNQ');
        Route::get('asyncKTTM', 'ProductionPlanController@asyncKTTM')->name('asyncKTTM');
        Route::get('edit/{id}', 'ProductionPlanController@edit')->name('edit');
        Route::get('exportExcel', 'ProductionPlanController@exportExcel')->name('exportExcel');
        Route::post('store', 'ProductionPlanController@store')->name('store');
        Route::post('updateKTNQ/{id}', 'ProductionPlanController@updateKTNQ')->name('updateKTNQ');
        Route::post('updateKTTM/{id}', 'ProductionPlanController@updateKTTM')->name('updateKTTM');
        Route::post('action', 'ProductionPlanController@action')->name('action');
        Route::put('trashed/revert/{id}', 'ProductionPlanController@revertFromTrash')->name('trashed.revert');
        Route::delete('trashed/destroy/{id}', 'ProductionPlanController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * DynamicColumnController Routes
     */
    Route::prefix('dynamicColumns')->name('dynamicColumns.')->group(function () {
        Route::get('', 'DynamicColumnController@index')->name('index');
        Route::get('create', 'DynamicColumnController@create')->name('create');
        Route::get('edit/{id}', 'DynamicColumnController@edit')->name('edit');
        Route::get('exportExcel', 'DynamicColumnController@exportExcel')->name('exportExcel');
        Route::post('store', 'DynamicColumnController@store')->name('store');
        Route::post('update', 'DynamicColumnController@update')->name('update');
        Route::post('action', 'DynamicColumnController@action')->name('action');
        Route::put('trashed/revert/{id}', 'DynamicColumnController@revertFromTrash')->name('trashed.revert');
        Route::delete('trashed/destroy/{id}', 'DynamicColumnController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Log Import Management Routes
     */
    Route::prefix('logImports')->name('logImports.')->group(function () {
        Route::get('', 'LogImportController@index')->name('index');
        Route::get('create', 'LogImportController@create')->name('create');
        Route::get('edit/{id}', 'LogImportController@edit')->name('edit');
        Route::get('exportExcel', 'LogImportController@exportExcel')->name('exportExcel');
        Route::post('store', 'LogImportController@store')->name('store');
        Route::post('update', 'LogImportController@update')->name('update');
        Route::post('action', 'LogImportController@action')->name('action');
        Route::put('trashed/revert/{id}', 'LogImportController@revertFromTrash')->name('trashed.revert');
        Route::delete('trashed/destroy/{id}', 'LogImportController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Required Management Routes
     */
    Route::prefix('requireds')->name('requireds.')->group(function () {
        Route::get('', 'RequiredController@index')->name('index');
        Route::get('index_confirm', 'RequiredController@index_confirm')->name('indexConfirm');
        Route::get('report', 'RequiredController@report')->name('report');
        Route::get('requiredWithDelete', 'RequiredController@requiredWithDelete')->name('requiredWithDelete');
        Route::get('create', 'RequiredController@create')->name('create');
        Route::get('createPrintPdf', 'RequiredController@createPrintPdf')->name('createPrintPdf');
        Route::post('checkRequired', 'RequiredController@checkRequired')->name('checkRequired');
        Route::get('edit/{id}', 'RequiredController@edit')->name('edit');
        Route::post('complete', 'RequiredController@complete')->name('complete');
        Route::get('exportExcel', 'RequiredController@exportExcel')->name('exportExcel');
        Route::get('exportExcelReport', 'RequiredController@exportExcelReport')->name('exportExcelReport');
        Route::post('store', 'RequiredController@store')->name('store');
        Route::post('update', 'RequiredController@update')->name('update');
        Route::post('action', 'RequiredController@action')->name('action');
        Route::post('showDataAccessorys', 'RequiredController@showDataAccessorys')->name('showDataAccessorys');
        Route::post('ajaxSuggestions', 'RequiredController@ajaxSuggestions')->name('ajaxSuggestions');
        Route::post('requireCheckListMachineCut', 'RequiredController@requireCheckListMachineCut')->name('requireCheckListMachineCut');
        Route::put('trashed/revert', 'RequiredController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy', 'RequiredController@destroyTrash')->name('trashed.destroy');
    });

     /**
     * Required Management Routes
     */
    Route::prefix('requestVpp')->name('requestVpps.')->group(function () {
        Route::get('', 'VPPController@index')->name('index');
        Route::get('index_confirm', 'VPPController@index_confirm')->name('indexConfirm');
        Route::get('report', 'VPPController@report')->name('report');
        Route::get('requiredWithDelete', 'VPPController@requiredWithDelete')->name('requiredWithDelete');
        Route::get('create', 'VPPController@create')->name('create');
        Route::get('createPrintPdf', 'VPPController@createPrintPdf')->name('createPrintPdf');
        Route::post('checkRequired', 'VPPController@checkRequired')->name('checkRequired');
        Route::get('edit/{id}', 'VPPController@edit')->name('edit');
        Route::post('complete', 'VPPController@complete')->name('complete');
        Route::get('exportExcel', 'VPPController@exportExcel')->name('exportExcel');
        Route::get('exportExcelReport', 'VPPController@exportExcelReport')->name('exportExcelReport');
        Route::post('store', 'VPPController@store')->name('store');
        Route::post('update', 'VPPController@update')->name('update');
        Route::post('action', 'VPPController@action')->name('action');
        Route::post('showDataAccessorys', 'VPPController@showDataAccessorys')->name('showDataAccessorys');
        Route::post('ajaxSuggestions', 'RequiredController@ajaxSuggestions')->name('ajaxSuggestions');
        Route::post('requireCheckListMachineCut', 'VPPController@requireCheckListMachineCut')->name('requireCheckListMachineCut');
        Route::put('trashed/revert', 'VPPController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy', 'VPPController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Warehouses Management Routes
     */
    Route::prefix('warehouses')->name('warehouses.')->group(function () {
        Route::get('', 'WareHouseController@index')->name('index');
        Route::get('index_ong', 'WareHouseController@index_ong')->name('index_ong');
        Route::get('report', 'WareHouseController@report')->name('report');
        Route::get('create', 'WareHouseController@create')->name('create');
        Route::post('action', 'WareHouseController@action')->name('action');
        Route::get('edit/{id}', 'WareHouseController@edit')->name('edit');
        Route::get('exportExcel', 'WareHouseController@exportExcel')->name('exportExcel');
        Route::get('exportExcelReport', 'WareHouseController@exportExcelReport')->name('exportExcelReport');
        Route::put('trashed/revert/{id}', 'WareHouseController@revertFromTrash')->name('trashed.revert');
        Route::get('trashed/destroy/{id}', 'WareHouseController@destroyTrash')->name('trashed.destroy');
        Route::post('complete', 'WareHouseController@complete')->name('complete');
        Route::post('checkLocaltion', 'WareHouseController@checkLocaltion')->name('checkLocaltion');
        Route::get('createPrintPdf', 'WareHouseController@createPrintPdf')->name('createPrintPdf');
        Route::post('checkRequired', 'WareHouseController@checkRequired')->name('checkRequired');
    });

    /**
     * Accessory Management Routes
     */
    Route::prefix('accessorys')->name('accessorys.')->group(function () {
        Route::get('', 'AccessoryController@index')->name('index');
        Route::get('create', 'AccessoryController@create')->name('create');
        Route::get('edit/{id}', 'AccessoryController@edit')->name('edit');
        Route::get('exportExcel', 'AccessoryController@exportExcel')->name('exportExcel');
        Route::get('syncAccessory', 'AccessoryController@syncAccessory')->name('syncAccessory');
        Route::post('store', 'AccessoryController@store')->name('store');
        Route::post('action', 'AccessoryController@action')->name('action');
        Route::post('saveInvoice', 'AccessoryController@saveInvoice')->name('saveInvoice');
        Route::post('update/{id}', 'AccessoryController@update')->name('update');
        Route::get('trashed/destroy/{id}', 'AccessoryController@destroyTrash')->name('trashed.destroy');
    });

     /**
     * Tool Routes
     */
    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('', 'ToolsController@index')->name('index');
        Route::get('create', 'ToolsController@create')->name('create');
        Route::get('edit/{id}', 'ToolsController@edit')->name('edit');
        Route::get('exportExcel', 'ToolsController@exportExcel')->name('exportExcel');
        Route::post('action', 'ToolsController@action')->name('action');
        Route::post('update/{id}', 'ToolsController@update')->name('update');
    });


     /**
     * Tool Routes
     */
    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('', 'QuestionController@index')->name('index');
        Route::get('create', 'QuestionController@create')->name('create');
        Route::get('edit/{id}', 'QuestionController@edit')->name('edit');
        Route::get('exportExcel', 'QuestionController@exportExcel')->name('exportExcel');
        Route::post('action', 'QuestionController@action')->name('action');
        Route::post('update/{id}', 'QuestionController@update')->name('update');
    });

      /**
     * Test Exam Routes
     */
    Route::prefix('testExams')->name('testExams.')->group(function () {
        Route::get('', 'TestExamsController@index')->name('index');
        Route::get('create', 'TestExamsController@create')->name('create');
        Route::get('edit/{id}', 'TestExamsController@edit')->name('edit');
        Route::get('exportExcel', 'TestExamsController@exportExcel')->name('exportExcel');
        Route::post('action', 'TestExamsController@action')->name('action');
        Route::post('update/{id}', 'TestExamsController@update')->name('update');
    });

    /**
     * CheckDevice Management Routes
     */
    Route::prefix('checkdevices')->name('checkdevices.')->group(function () {
        Route::get('', 'CheckDeviceController@index')->name('index');
        Route::get('list', 'CheckDeviceController@index_list')->name('index_list');
        Route::get('checklist_realtime', 'CheckDeviceController@checklist_realtime')->name('checklist_realtime');
        Route::get('create', 'CheckDeviceController@create')->name('create');
        Route::get('edit/{id}', 'CheckDeviceController@edit')->name('edit');
        Route::get('exportExcel', 'CheckDeviceController@exportExcel')->name('exportExcel');
        Route::post('action', 'CheckDeviceController@action')->name('action');
        Route::post('store', 'CheckDeviceController@store')->name('store');
        Route::post('update/{id}', 'CheckDeviceController@update')->name('update');
    });

    /**
     * Signature Submission Management Routes
     */
    Route::prefix('signatureSubmissions')->name('signatureSubmissions.')->group(function () {
        Route::get('', 'SignatureSubmissionController@index')->name('index');
        Route::get('create', 'SignatureSubmissionController@create')->name('create');
        Route::get('edit/{id}', 'SignatureSubmissionController@edit')->name('edit');
        Route::get('exportExcel', 'SignatureSubmissionController@exportExcel')->name('exportExcel');
        Route::post('store', 'SignatureSubmissionController@store')->name('store');
        Route::post('update', 'SignatureSubmissionController@update')->name('update');
        Route::post('action', 'SignatureSubmissionController@action')->name('action');
        Route::put('trashed/revert/{id}', 'SignatureSubmissionController@revertFromTrash')->name('trashed.revert');
        Route::delete('trashed/destroy/{id}', 'SignatureSubmissionController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Check cutting machine every day
     */
    Route::prefix('checkCutMachine')->name('checkCutMachine.')->group(function () {
        Route::get('/', 'CheckCutMachineController@index')->name('index');
        Route::get('/create', 'CheckCutMachineController@create')->name('create');
        // Route::any('/create', 'CheckCutMachineController@create')->name('create');
        // Route::match(['get', 'post'], '/create', 'CheckCutMachineController@create')->name('create');

        // Route::get('/show', 'CheckCutMachineController@show')->name('show');
        Route::post('import', 'CheckCutMachineController@importExcelData')->name('importExcelData');
        Route::get('exportExcel', 'CheckCutMachineController@exportExcel')->name('exportExcel');
        Route::post('action', 'CheckCutMachineController@action')->name('action');
        Route::get('trashed/destroy/{id}', 'CheckCutMachineController@destroyTrash')->name('trashed.destroy');
    });

    /**
     * Productivity Management Routes
     */
    Route::prefix('productvt')->name('productvt.')->group(function () {
        Route::get('', 'ProductvtController@index')->name('index');
        Route::get('/user-input', 'ProductvtController@UserInput')->name('user-input');
        Route::get('/edit', 'ProductvtController@ProductvtEdit')->name('edit');
        Route::post('', 'ProductvtController@ProductvtData')->name('view');
    });

    /**
     * 張力を確認してください / Kiểm tra sức căng / Check Tension
     */
    Route::prefix('checkTension')->name('checkTension.')->group(function () {
        Route::get('/', 'CheckTensionController@index')->name('index');
        Route::post('/complete', 'CheckTensionController@saveData')->name('complete');
        Route::get('/view', 'CheckTensionController@viewData')->name('view');
        Route::get('/exportExcel', 'CheckTensionController@exportExcel')->name('exportExcel');
    });
});

/**
 * Contact Routes
 */
Route::group(['prefix' => ''], function () {
    Route::resource('contacts', ContactsController::class);
});

/**
 * Settings Management Routes
 */
Route::group(['prefix' => 'settings'], function () {
    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/update', [SettingsController::class, 'update'])->name('settings.update');
    Route::resource('languages', LanguagesController::class);
});

Route::get('reset-cache', [CacheController::class, 'reset_cache']);
