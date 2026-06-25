<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoftwareRequestController;
use App\Http\Controllers\DevelopmentProjectController;
use App\Http\Controllers\DashboardController;
use App\Models\Programmer;
use App\Models\DevelopmentProject;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Modul Manajemen Request / Tiket SDRF
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [SoftwareRequestController::class, 'index'])->name('index');
        Route::get('/create', [SoftwareRequestController::class, 'create'])->name('create');
        Route::post('/', [SoftwareRequestController::class, 'store'])->name('store');
        Route::get('/{id}', [SoftwareRequestController::class, 'show'])->name('show');
        Route::get('/{id}/file', [SoftwareRequestController::class, 'viewFile'])->name('view-file');
        Route::patch('/{id}/review', [SoftwareRequestController::class, 'review'])->name('review');

        Route::post('/api/programmer-workload', [SoftwareRequestController::class, 'getProgrammerWorkload'])->name('api.programmer-workload');
    });

    // Modul Manajemen Proyek Development & Story Points
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [DevelopmentProjectController::class, 'index'])->name('index');
        Route::get('/{id}', [DevelopmentProjectController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [DevelopmentProjectController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/uat', [DevelopmentProjectController::class, 'submitUat'])->name('submit-uat');
        Route::post('/{id}/close', [DevelopmentProjectController::class, 'closeProject'])->name('close');
        Route::post('/{id}/rating', [DevelopmentProjectController::class, 'storeRating'])->name('store-rating');
        Route::post('/{id}/resume', [DevelopmentProjectController::class, 'resumeProject'])->name('resume');
    });

    // Modul Log Audit & Metrik/Grafik (Dashboard Analytics)
    Route::prefix('metrics')->name('metrics.')->group(function () {
        Route::get('/workload', function () {
            return 'Data JSON/View Beban Kerja Programmer';
        })->name('workload');
    });

    // route profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
