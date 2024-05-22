<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WpController;
use App\Http\Controllers\DasboardController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\WpCredentialController;
use App\Http\Controllers\iaCredentialController;
use App\Http\Controllers\WpPluginController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// get
Route::get('/routes', [DasboardController::class, 'listRoutes'])->name('api.routes');

Route::get('/post/{id}', [DasboardController::class, 'getPost'])->name('post');
Route::get('/posts', [DasboardController::class, 'allPosts'])->name('posts');
Route::get('/project/{id}',[DasboardController::class,'listProjectItems'])->name('listProjectItems');
Route::get('/editor/{id}',[DasboardController::class,'getEditor'])->name('getEditor');
Route::get('/editors',[DasboardController::class,'allEditors'])->name('allEditors');
Route::get('/sites',[DasboardController::class,'allSites'])->name('allSites');
Route::get('/plugin/ping',[WpPluginController::class,'ping'])->name('ping');

// post


// put update
Route::put('/project/{id}', [DasboardController::class, 'updateProject'])->name('updateProject');

// delete
Route::delete('/editor/{id}', [EditorController::class,'destroy'])->name('editor.destroy');
Route::delete('/wp_crential/{id}',[WpCredentialController::class,'deleteWpCredential'])->name('credentialDelete');
Route::delete('/delete_token/{id}',[iaCredentialController::class,'deleteCredential'])->name('deleteToken');
Route::delete('/project/{id}',[DasboardController::class,'deleteProject'])->name('deleteProject');


