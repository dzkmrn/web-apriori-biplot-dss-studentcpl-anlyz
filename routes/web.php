<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMahasiswaController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\CplController;
use App\Http\Controllers\ProfilLulusanController;
use App\Http\Controllers\ResetDataController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;

// Clear Cache Route (untuk hosting)
Route::get('/clear-cache-hosting', function() {
    try {
        // Clear various caches
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        $commands = [
            'config:clear' => Artisan::output(),
        ];
        
        Artisan::call('cache:clear');
        $commands['cache:clear'] = Artisan::output();
        
        Artisan::call('view:clear');
        $commands['view:clear'] = Artisan::output();
        
        Artisan::call('route:clear');
        $commands['route:clear'] = Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => 'All caches cleared successfully!',
            'details' => $commands,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error clearing cache: ' . $e->getMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ], 500);
    }
});

// Clear Cache Page (untuk hosting)
Route::get('/clear-cache', function() {
    return view('clear-cache');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // Data Mahasiswa Management
    Route::prefix('data-mahasiswa')->name('data-mahasiswa.')->group(function () {
        Route::get('/', [DataMahasiswaController::class, 'index'])->name('index');
        Route::get('/create', [DataMahasiswaController::class, 'create'])->name('create');
        Route::post('/', [DataMahasiswaController::class, 'store'])->name('store');
        
        // Import & Export (harus sebelum route parameter)
        Route::get('/import/form', [DataMahasiswaController::class, 'importForm'])->name('import.form');
        Route::post('/import', [DataMahasiswaController::class, 'import'])->name('import');
        Route::post('/normalize', [DataMahasiswaController::class, 'normalize'])->name('normalize');
        Route::get('/download', [DataMahasiswaController::class, 'download'])->name('download');
        
        // Routes dengan parameter harus di akhir
        Route::get('/{mahasiswa}', [DataMahasiswaController::class, 'show'])->name('show');
        Route::get('/{mahasiswa}/edit', [DataMahasiswaController::class, 'edit'])->name('edit');
        Route::put('/{mahasiswa}', [DataMahasiswaController::class, 'update'])->name('update');
        Route::delete('/{mahasiswa}', [DataMahasiswaController::class, 'destroy'])->name('destroy');
    });

    // Analisis Hasil (Apriori Analysis)
    Route::prefix('analisis')->name('analisis.')->group(function () {
        Route::get('/', [AnalisisController::class, 'index'])->name('index');
        Route::get('/create', [AnalisisController::class, 'create'])->name('create');
        Route::post('/', [AnalisisController::class, 'store'])->name('store');
        Route::get('/{analisis}', [AnalisisController::class, 'show'])->name('show');
        Route::delete('/{analisis}', [AnalisisController::class, 'destroy'])->name('destroy');
        Route::get('/{analisis}/chart-data', [AnalisisController::class, 'getChartData'])->name('chart-data');
    });

    // CPL Management  
    Route::prefix('cpl')->name('cpl.')->group(function () {
        Route::get('/', [CplController::class, 'index'])->name('index');
        Route::get('/create', [CplController::class, 'create'])->name('create');
        Route::post('/', [CplController::class, 'store'])->name('store');
        Route::get('/{cpl}', [CplController::class, 'show'])->name('show');
        Route::get('/{cpl}/edit', [CplController::class, 'edit'])->name('edit');
        Route::put('/{cpl}', [CplController::class, 'update'])->name('update');
        Route::delete('/{cpl}', [CplController::class, 'destroy'])->name('destroy');
        Route::post('/{cpl}/toggle-active', [CplController::class, 'toggleActive'])->name('toggle-active');
    });

    // Profil Lulusan Management
    Route::prefix('profil-lulusan')->name('profil-lulusan.')->group(function () {
        Route::get('/', [ProfilLulusanController::class, 'index'])->name('index');
        Route::get('/create', [ProfilLulusanController::class, 'create'])->name('create');
        Route::post('/', [ProfilLulusanController::class, 'store'])->name('store');
        Route::get('/{profilLulusan}', [ProfilLulusanController::class, 'show'])->name('show');
        Route::get('/{profilLulusan}/edit', [ProfilLulusanController::class, 'edit'])->name('edit');
        Route::put('/{profilLulusan}', [ProfilLulusanController::class, 'update'])->name('update');
        Route::delete('/{profilLulusan}', [ProfilLulusanController::class, 'destroy'])->name('destroy');
        Route::post('/{profilLulusan}/toggle-active', [ProfilLulusanController::class, 'toggleActive'])->name('toggle-active');
    });

    // Reset Data Management
    Route::prefix('reset-data')->name('reset-data.')->group(function () {
        Route::get('/', [ResetDataController::class, 'index'])->name('index');
        Route::get('/confirm', [ResetDataController::class, 'confirm'])->name('confirm');
        Route::post('/reset', [ResetDataController::class, 'reset'])->name('reset');
    });

    // Welcome route (redirect to dashboard)
    Route::get('/welcome', function () {
        return redirect()->route('dashboard');
    });
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/apriori-redirect', [HomeController::class, 'aprioriRedirect'])->name('apriori.redirect');
