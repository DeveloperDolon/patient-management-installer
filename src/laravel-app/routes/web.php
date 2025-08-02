<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AssociatedSitesController,
    AuthController,
    ClinicController,
    StatisticsController,
    IllnessesController,
    ComplaintsController,
    ConcomitantDiseasesController,
    InvestigationsController,
    ExaminationsController,
    MedicinesController,
    MenstrualDiseasesController,
    ObstetricController,
    OperationController,
    PatientsController,
    PersonalInfoController,
    PrescriptionsController,
    SpecialProceduresController,
    VaccinationController
};

Route::view('/', 'app');
Route::view('/login', 'app')->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::prefix('dashboard')
    ->middleware('auth.session')
    ->name('dashboard')
    ->group(function () {
        Route::get('/', [StatisticsController::class, 'index'])->name('');

        Route::prefix('illnesses')->name('.illnesses')
            ->controller(IllnessesController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('complaints')->name('.complaints')
            ->controller(ComplaintsController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('concomitant-diseases')->name('.concomitant-diseases')
            ->controller(ConcomitantDiseasesController::class)
            ->group(function () {
                Route::get('', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('investigations')->name('.investigations')
            ->controller(InvestigationsController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('examinations')->name('.examinations')
            ->controller(ExaminationsController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('medicines')->name('.medicines')
            ->controller(MedicinesController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('menstrual-diseases')
            ->name('.menstrual-diseases')
            ->controller(MenstrualDiseasesController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('patients')
            ->name('.patients')
            ->controller(PatientsController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::get('/create', 'create')->name('.create');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
                Route::get('/details/{id}', 'details')->name('.details');
                Route::get('/edit/{id}', 'edit')->name('.edit');
                Route::get('/prescriptions/{id}', 'prescriptions')->name('.prescriptions');
            });

        Route::prefix('prescriptions')
            ->name('.prescriptions')
            ->controller(PrescriptionsController::class)
            ->group(function () {
                Route::get('/', 'create')->name('.create');
                Route::post('/', 'store')->name('.store');
                Route::get('/show/{id}', 'show')->name('.show');
                Route::get('/edit/{id}', 'edit')->name('.edit');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
                Route::delete('/image/delete/{id}', 'deleteImage')->name('.image-delete');
                Route::get('/{prescription}/print', 'print')->name('.print');
            });

        Route::prefix('special-procedures')
            ->name('.special-procedures')
            ->controller(SpecialProceduresController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('associated-sites')
            ->name('.associated-sites')
            ->controller(AssociatedSitesController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('personal-infos')
            ->name('.personal-infos')
            ->controller(PersonalInfoController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('clinics')
            ->name('.clinics')
            ->controller(ClinicController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::get('/create', 'create')->name('.create');
                Route::post('/', 'store')->name('.store');
                Route::get('/show/{id}', 'show')->name('.show');
                Route::get('/edit/{id}', 'edit')->name('.edit');
                Route::put('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
                Route::put('/toggle-status/{id}', 'toggleStatus')->name('.toggleStatus');
            });

        Route::prefix('vaccinations')
            ->name('.vaccinations')
            ->controller(VaccinationController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('obstetrics')
            ->name('.obstetrics')
            ->controller(ObstetricController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });

        Route::prefix('operations')
            ->name('.operations')
            ->controller(OperationController::class)
            ->group(function () {
                Route::get('/', 'index')->name('');
                Route::post('/', 'store')->name('.store');
                Route::post('/update/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'delete')->name('.delete');
            });
    });
