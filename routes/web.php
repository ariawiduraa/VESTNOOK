<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LahanController;


Route::get('/', function () {
    return view('index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

    // Profile Settings
    Route::get ('/profile/settings', [App\Http\Controllers\ProfileController::class, 'edit'])  ->name('profile.settings');
    Route::post('/profile/settings', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Analisis (create)
    Route::get( '/analisis', [App\Http\Controllers\AnalisisController::class, 'index'])->name('analisis.index');
    Route::post('/analisis', [LahanController::class, 'store'])->name('analisis.store');

    // Lahan resource
    Route::get   ('/lahan/compare',                 [LahanController::class, 'compare'])        ->name('lahan.compare');
    Route::get   ('/lahan/compare-insight',         [LahanController::class, 'compareInsight']) ->name('lahan.compare-insight');
    Route::get   ('/lahan/{lahan}',               [LahanController::class, 'show'])           ->name('lahan.show');
    Route::get   ('/lahan/{lahan}/edit',            [LahanController::class, 'edit'])           ->name('lahan.edit');
    Route::put   ('/lahan/{lahan}',                 [LahanController::class, 'update'])         ->name('lahan.update');
    Route::delete('/lahan/{lahan}',                 [LahanController::class, 'destroy'])        ->name('lahan.destroy');
    Route::get   ('/lahan/{lahan}/insight',         [LahanController::class, 'insight'])        ->name('lahan.insight');
    Route::get   ('/lahan/{lahan}/statistik',       [LahanController::class, 'statistik'])      ->name('lahan.statistik');
    Route::get   ('/lahan/{lahan}/insight-statistik',[LahanController::class,'insightStatistik'])->name('lahan.insight.statistik');
    Route::get   ('/lahan/{lahan}/schedule',        [LahanController::class, 'schedule'])        ->name('lahan.schedule');
    Route::post  ('/lahan/{lahan}/schedule-toggle', [LahanController::class, 'scheduleToggle'])  ->name('lahan.schedule.toggle');



    // ─── Admin Panel ───
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/users/{user}/toggle', [App\Http\Controllers\AdminController::class, 'toggleAdmin'])->name('admin.users.toggle');
    Route::delete('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/admin/chart-data', [App\Http\Controllers\AdminController::class, 'chartData'])->name('admin.chart.data');
});

Route::get('/qr-generator', function () {
    return view('qr-generator');
})->name('qr-generator');

Route::get('/predict-dummy', [App\Http\Controllers\LahanController::class, 'predictDummy'])->name('predict-dummy');

