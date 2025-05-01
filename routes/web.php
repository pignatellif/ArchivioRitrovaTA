<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SectionController;

use Illuminate\Http\Request;

// ==========================
// SEZIONI PRINCIPALI
// ==========================
Route::controller(SectionController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/video/{id}', 'video')->name('video.show');
    Route::get('/archivio', 'archivio')->name('archivio');
    Route::get('/serie', 'serie')->name('serie');
    Route::get('/fuori-dal-frame', 'fuoriDalFrame')->name('fuori_dal_frame');
    Route::get('/fuori-dal-tacco', 'fuoriDalTacco')->name('fuori_dal_tacco');
    Route::get('/eventi', 'eventi')->name('eventi');
    Route::get('/sostienici', 'sostienici')->name('sostienici');
    Route::get('/chi-siamo', 'info')->name('chi_siamo');
    Route::get('/fuori-dal-frame/registi', 'registi')->name('registi');
    Route::get('/fuori-dal-frame/personaggi', 'personaggi')->name('personaggi');
});


// API Resource per i video
Route::apiResource('videos', VideoController::class);

// ==========================
// AUTENTICAZIONE
// ==========================
Auth::routes();
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===========================================
// AREA ADMIN (Protetta con Middleware 'auth')
// ===========================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // ==========================
    // VIDEO
    // ==========================
    Route::prefix('admin/videos')->name('videos.')->group(function () {
        Route::get('/', [VideoController::class, 'index'])->name('index');  
        Route::get('/create', [VideoController::class, 'create'])->name('create');  // This line defines the videos.create route
        Route::post('/', [VideoController::class, 'store'])->name('store');  
        Route::get('/{video}/edit', [VideoController::class, 'edit'])->name('edit');  
        Route::put('/{video}', [VideoController::class, 'update'])->name('update');  
        Route::delete('/{video}', [VideoController::class, 'destroy'])->name('destroy');  
    });    

    // ==========================
    // SERIE
    // ==========================
    Route::prefix('admin/series')->name('series.')->group(function () {
        Route::get('/', [SeriesController::class, 'index'])->name('index'); 
        Route::get('/create', [SeriesController::class, 'create'])->name('create'); 
        Route::post('/', [SeriesController::class, 'store'])->name('store'); 
        Route::get('/{id}/edit', [SeriesController::class, 'edit'])->name('edit');  
        Route::put('/{id}', [SeriesController::class, 'update'])->name('update');        
        Route::delete('/{series}', [SeriesController::class, 'destroy'])->name('destroy'); 
    });    

    // ==========================
    // EVENTI
    // ==========================
    Route::prefix('admin/events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');  // Lista
        Route::get('/create', [EventController::class, 'create'])->name('create');  // Form creazione
        Route::post('/', [EventController::class, 'store'])->name('store');  // Salvataggio
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');  // Modifica
        Route::put('/{event}', [EventController::class, 'update'])->name('update');  // Aggiornamento
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');  // Eliminazione
    });

});
