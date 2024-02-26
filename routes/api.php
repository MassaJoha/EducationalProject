<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\PurchaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Logout api route
    Route::post('/logout', [AuthController::class, 'logout']);

    // Package Routes
    // Get all Packages with Package name filter route
    Route::get('/packages', [PackageController::class, 'index']);
    // Get all Package route
    Route::get('/packages/{id}', [PackageController::class, 'show']);
    // Set a new Package route
    Route::post('/packages', [PackageController::class, 'store']);
    // Edit Package route
    Route::get('/packages/{id}/edit', [PackageController::class, 'edit']);
    // Update a Package route
    Route::put('/packages/{package}', [PackageController::class, 'update']);
    // Delete a specific Package route
    Route::delete('/packages/{package}', [PackageController::class, 'destroy']);
    // Upload media for specific Package route
    Route::post('/packages/{package}/media', [PackageController::class, 'addMedia']);
    // Get all media's for specific Package route
    Route::get('/packages/{package}/media', [PackageController::class, 'getAllMediaForPackage']);
    
    // Course Routes
    // Get all Courses with Course name and Package ID filters route
    Route::get('/courses', [CourseController::class, 'index']);
    // Get all Course route
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    // Set a new Course route
    Route::post('/courses', [CourseController::class, 'store']);
    // Edit Course route
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit']);
    // Update a Course route
    Route::put('/courses/{course}', [CourseController::class, 'update']);
    // Delete a specific Course route
    Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
    // Upload media for specific Course route
    Route::post('/courses/{course}/media', [CourseController::class, 'addMedia']);
    // Get all media's for specific Course route
    Route::get('/courses/{course}/media', [CourseController::class, 'getAllMediaForCourse']);

    // Purchase Routes
    // Get all purchases for specific User route
    Route::get('/users/{user}/purchases', [PurchaseController::class, 'index']);
    // Create a new Purchase by specific User route
    Route::post('/users/{user}/purchases', [PurchaseController::class, 'store']);
});

Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);
Route::post('authenticate', [AuthController::class, 'authenticate']);
Route::get('verify/{token}', [AuthController::class, 'verify']);
