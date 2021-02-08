<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleslogsController;


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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
    Route::get('/saleslogs',[SaleslogsController::class, 'index'])->name('saleslogs');
    //Route::resource('/profile',[ProfileController::class, 'index'])->name('profile');

});



Route::get('/profile-page', function(){
  return view('profile-page');
})->middleware(['auth'])->name('view.profile');

require __DIR__.'/auth.php';
