<?php

use App\Http\Controllers\CsvReaderController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\DasboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GooGleDocsController;
use App\Http\Controllers\GptController;
use App\Http\Controllers\PostContentController;
use App\Services\PostFileService;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/register', [DasboardController::class,'register'])->name('dashboard.register');
Route::get('/upload_csv',[CsvReaderController::class,'showUploadForm']);
Route::get('/', [DasboardController::class, 'index']);
Route::get('/dashboard', [DasboardController::class, 'show'])->name('dashboard.show');
Route::get('/profile', [DasboardController::class, 'profile'])->name('dashboard.profile');
Route::get('/edior_created',[DasboardController::class,'ediorCreated'])->name('dashboard.editorCreated');
Route::get('/content_creation',[DasboardController::class,'contentCreation'])->name('dashboard.contentConfig');
Route::get('/post_creation',[DasboardController::class,'postCreation'])->name('dashboard.createPost');
Route::get('/upload_doc',[DasboardController::class,'DocsUpload'])->name('doc');
Route::get('/auth/google', [GoogleAuthController::class,'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class,'handleGoogleCallback'])->name('google.callback');


Route::post('/createEditor', [EditorController::class,'processEditor'])->name('processEditor');
Route::post('/submit_file',[CsvReaderController::class,'ImportCsv']);
Route::post('/insert_post_content',[PostContentController::class,'saveContent'])->name('insertContent');
Route::post('/gpt_query',[GptController::class,'generatePost']);
Route::post('/process_doc',[GooGleDocsController::class,'insertDocOnDB']);