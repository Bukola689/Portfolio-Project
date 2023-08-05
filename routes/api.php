<?php

use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\V1\Admin\AdminPermissionController;
use App\Http\Controllers\V1\Admin\AdminRoleController;
use App\Http\Controllers\V1\Admin\CategoryController;
use App\Http\Controllers\V1\Admin\PostController;
use App\Http\Controllers\V1\Admin\TagController;
use App\Http\Controllers\V1\Admin\UserController;
use App\Http\Controllers\V1\Admin\VerifyEmailController;
use App\Http\Controllers\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\Auth\LoginController;
use App\Http\Controllers\V1\Auth\RegisterController;
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

           Route::get('/posts', [PostController::class, 'index']);

        //....auth....//
        Route::group(['prefix'=> 'auth'], function() {
            Route::post('register', [RegisterController::class, 'register']);
            Route::post('login', [LoginController::class, 'login']);
            Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
         Route::group(['middleware' => 'auth:sanctum'], function() {
            Route::post('logout', [LogoutController::class, 'logout']);
            Route::post('/email/verification-notification', [VerifyEmailController::class, 'resendNotification'])->name('verification.send');
            Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']); 
 
         });
     });


     Route::group(['prefix' => 'me', 'middleware' => 'auth:sanctum'], function() {
        Route::post('/profiles', [ProfileController::class, 'updateProfile']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
       });


       //Route::group(['middleware' => ['auth:sanctum']], function() {
        Route::group(['middleware' => [], 'prefix' => 'admin'], function() {
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);
        Route::post('users/{id}/suspend', [UserController::class, 'suspend']);
        Route::post('users/{id}/active', [UserController::class, 'active']);
        Route::get('users/{id}/roles', [AdminRoleController::class, 'show']);
        Route::get('users/{id}/permissions', [AdminPermissionController::class, 'show']);
        Route::post('users/{id}/roles', [AdminRoleController::class, 'changeRole']);

        Route::group(['prefix' => 'categories'], function () {

            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::get('/{id}', [CategoryController::class, 'show']);
            Route::patch('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
    
            });

            Route::group(['prefix' => 'posts'], function () {
        
                Route::get('/', [PostController::class, 'index']);
                Route::post('/', [PostController::class, 'store']);
                Route::get('/{id}', [PostController::class, 'show']);
                Route::put('/{id}', [PostController::class, 'update']);
                Route::delete('/{id}', [PostController::class, 'destroy']);
           
               });
       
            Route::group(['prefix' => 'tags'], function () {
       
               Route::get('/', [TagController::class, 'index']);
               Route::post('/', [TagController::class, 'store']);
               Route::get('/{id}', [TagController::class, 'show']);
               Route::patch('/{id}', [TagController::class, 'update']);
               Route::delete('/{id}', [TagController::class, 'destroy']);
           
               });
    
        }); 
  
    //}); 

        