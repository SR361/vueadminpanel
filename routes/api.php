<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\AppointmentStatusController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategorieController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\CurrencieController;
use App\Http\Controllers\API\ProjectCategories;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['ApiLocalization'])->prefix('v1')->namespace('API')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['accessToken'])->group(function () {
        Route::get('/categories', [CategorieController::class, 'index']);
        Route::post('/categories', [CategorieController::class, 'store']);
        Route::put('/categories/{categorie}', [CategorieController::class, 'update']);
        Route::delete('/categories/{categorie}', [CategorieController::class, 'destory']);
        Route::delete('/categories', [CategorieController::class, 'bulkDelete']);
        Route::get('/parent-categories-list', [CategorieController::class, 'parentCategorieList']);
        Route::post('/child-categories-list', [CategorieController::class, 'childCategorieList']);

        Route::get('/child-categorie/{slug}/list', [CategorieController::class, 'childCategorie']);
        Route::get('/parent-categorie/{slug}/list', [CategorieController::class, 'parentCategorie']);
        Route::post('/child-categories', [CategorieController::class, 'Childstore']);
        Route::put('/child-categories/{categorie}', [CategorieController::class, 'Childupdate']);
        Route::delete('/child-categories/{categorie}', [CategorieController::class, 'childDestory']);

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/allusers', [UserController::class, 'allUser']);
        Route::post('/users', [UserController::class, 'store']);
        Route::patch('/users/{user}/change-role', [UserController::class, 'changeRole']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destory']);
        Route::delete('/users', [UserController::class, 'bulkDelete']);
        Route::post('/updateprofile', [UserController::class, 'updateProfile']);
        Route::get('/getuserprofile', [UserController::class, 'getUserProfile']);
        Route::post('/changepassword', [UserController::class, 'changePassword']);
        Route::post('/createcompany', [UserController::class, 'createCompany']);
        Route::get('/getcompaneis', [UserController::class, 'getCompaneis']);

        Route::get('/clients', [ClientController::class, 'index']);

        Route::get('/appointment-status', [AppointmentStatusController::class, 'getStatusWithCount']);
        Route::get('/appointments', [AppointmentController::class, 'index']);
        Route::post('/appointments/create', [AppointmentController::class, 'store']);
        Route::patch('/appointments/{appointment}/change-status', [AppointmentController::class, 'changeStatus']);
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit']);
        Route::put('/appointments/{appointment}/edit', [AppointmentController::class, 'update']);
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);

        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/product/create', [ProductController::class, 'store']);
        Route::get('/product/{product}/edit', [ProductController::class, 'edit']);
        Route::post('/product/{product}/edit', [ProductController::class, 'update']);
        Route::get('/product-gallery-image', [ProductController::class, 'productGalleryImage']);
        Route::delete('/galleryimagedelete/{productgalleryimage}', [ProductController::class, 'galleryImageDelete']);
        Route::delete('/product/{product}', [ProductController::class, 'destroy']);
        Route::delete('/product', [ProductController::class, 'bulkDelete']);

        Route::post('/setting/generalsetting', [SettingController::class, 'generalSettingSave']);
        Route::get('/setting/getsetting', [SettingController::class, 'getSetting']);
        Route::get('/setting/resetsetting', [SettingController::class, 'resetSetting']);

        Route::get('/currencie', [CurrencieController::class, 'index']);

        Route::get('/project-categorie/all', [ProjectCategories::class, 'all']);
        Route::get('/project-categorie', [ProjectCategories::class, 'index']);
        Route::post('/project-categorie/create', [ProjectCategories::class, 'store']);
        Route::put('/project-categorie/{projectcategorie}', [ProjectCategories::class, 'update']);
        Route::delete('/project-categorie/delete/{projectcategorie}', [ProjectCategories::class, 'destroy']);
    });
});
