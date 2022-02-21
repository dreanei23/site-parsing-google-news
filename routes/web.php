<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\CategoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





Route::get('/', [IndexController::class, 'index'])->name('index');

Route::resources([
    'site' => SiteController::class,
]);
// 'news' => NewsController::class,

Route::get('{category:slug}', [CategoryController::class, 'show'])->name('category.show');


Route::get('{category:slug}/{news:slug}', [NewsController::class, 'show'])->name('news.show');
