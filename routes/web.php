<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApiController;
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

Route::get('/', function () {
    return view('welcome');
})->name('login1');


Route::post('login',[loginController::class,'store'])->name('login');
Route::get('ok',[loginController::class,'index'])->name('ok');


Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/registers', [RegisterController::class, 'store'])->name('register.store');




Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/message', [MessageController::class, 'index'])->name('message');
    Route::get('/all-message', [MessageController::class, 'allmessages'])->name('all.message');
    Route::post('/messages', [MessageController::class, 'store'])->name('message.store');
    Route::get('/fetch-messages', [MessageController::class, 'fetchMessages'])->name('message.fetch');
    Route::get('/active-users', [MessageController::class, 'activeUsers'])->name('users.active');
    Route::get('/online-users', [MessageController::class, 'onlineUsers'])->name('users.online');
    Route::post('/message/typing', [MessageController::class, 'typing'])->name('message.typing');
Route::get('/message/fetch-typing', [MessageController::class, 'fetchTyping'])->name('message.fetchTyping');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

Route::get('/fetch-api', [ApiController::class, 'fetchData'])->name('api.users');
Route::get('/live-user-stats', [ApiController::class, 'liveStats']);

   

});





Route::get('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'Logged out successfully');
})->name('logout');


