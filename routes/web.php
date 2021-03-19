<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleslogsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;


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
    if (Auth::check()) {
        return redirect('dashboard');
    }
    else
        return view('auth.login');
});


Route::middleware(['auth'])->group(function () {
Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/salesman/{sm_name}',[DashboardController::class, 'salesman_details'])->name('salesman-details');
Route::post('/dashboard/salesman/{sm_name}',[DashboardController::class, 'salesman_details'])->name('salesman-details');
Route::get('/sales',[SaleslogsController::class, 'index'])->name('saleslogs');
Route::get('/wholesales',[SaleslogsController::class, 'WholeSales'])->name('wholesaleslogs');

Route::get('sales/{id}',[SaleslogsController::class, 'showdetails'])->name('sales-view');

Route::get('/profile',[ProfileController::class, 'index'])->name('profile');
Route::resource('/user', UserController::class);
});


//Route::get('/profile-page', function(){
//return view('profile-page');
//})->middleware(['auth'])->name('view.profile');


require __DIR__.'/auth.php';
