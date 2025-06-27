<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\RiconoscimentoController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\EventContentController;

use Illuminate\Http\Request;

// ==========================
// SEZIONI PRINCIPALI
// ==========================
Route::controller(SectionController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/search', 'index')->name('search');
    Route::get('/video/{id}', 'video')->name('video.show');
    Route::get('/archivio', 'archivio')->name('archivio');
    Route::get('/serie', 'serie')->name('serie');
    Route::get('/fuori-dal-frame', 'fuoriDalFrame')->name('fuori_dal_frame');
    Route::get('/fuori-dal-tacco', 'fuoriDalTacco')->name('fuori_dal_tacco');
    Route::get('/eventi', 'eventi')->name('eventi');
    Route::get('/eventi/{id}', 'eventiShow')->name('eventi.show');
    Route::get('/chi-siamo', 'info')->name('chi_siamo');
    Route::get('/fuori-dal-frame/autori', 'autori')->name('autori');
    Route::get('/fuori-dal-frame/autori/{id}', 'showAutore')->name('autore.show');
    Route::get('/fuori-dal-frame/personaggi', 'personaggi')->name('personaggi');
    Route::get('/dicono-di-noi', 'diconoDiNoi')->name('dicono_di_noi');
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
        Route::get('/search-locations', [VideoController::class, 'searchLocations'])->name('search-locations');
        Route::get('/{id}/details', [VideoController::class, 'details'])->name('details');
    });    

    // ==========================
    // SERIE
    // ==========================
    Route::prefix('admin/series')->name('series.')->group(function () {
        Route::get('/', [SeriesController::class, 'index'])->name('index');
        Route::get('/create', [SeriesController::class, 'create'])->name('create');
        Route::post('/', [SeriesController::class, 'store'])->name('store');
        Route::get('/{serie}/edit', [SeriesController::class, 'edit'])->name('edit');    
        Route::put('/{serie}', [SeriesController::class, 'update'])->name('update');
        Route::delete('/{serie}', [SeriesController::class, 'destroy'])->name('destroy');
    });    

    // ==========================
    // EVENTI
    // ==========================
    Route::prefix('admin/events')->name('events.')->group(function () {
        // === CRUD EVENTI ===
        Route::get('/', [EventController::class, 'index'])->name('index');                  // Lista
        Route::get('/create', [EventController::class, 'create'])->name('create');          // Form creazione
        Route::post('/', [EventController::class, 'store'])->name('store');                 // Salvataggio
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');        // Modifica
        Route::put('/{event}', [EventController::class, 'update'])->name('update');         // Aggiornamento
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');    // Eliminazione
        Route::delete('/{event}/remove-cover-image', [EventController::class, 'removeCoverImage'])->name('removeCoverImage');

        // === CONTENTS degli eventi ===
        Route::get('/{event}/contents/create', [EventContentController::class, 'create'])->name('contents.create');
        Route::post('/{event}/contents', [EventContentController::class, 'store'])->name('contents.store');
        Route::put('/{event}/contents/{content}', [EventContentController::class, 'update'])->name('contents.update');
        Route::delete('/{event}/contents/{content}', [EventContentController::class, 'destroy'])->name('contents.destroy');
        // Aggiungi questa rotta per il riordinamento dei contenuti
        Route::post('/{event}/contents/reorder', [EventContentController::class, 'reorder'])->name('contents.reorder');
    });

    // ==========================
    // AUTORI
    // ==========================
    Route::prefix('admin/authors')->name('authors.')->group(function () {
        Route::get('/', [AuthorController::class, 'index'])->name('index');                 // Lista autori
        Route::get('/create', [AuthorController::class, 'create'])->name('create');         // Form creazione
        Route::post('/', [AuthorController::class, 'store'])->name('store');                // Salvataggio nuovo autore
        Route::get('/{author}/edit', [AuthorController::class, 'edit'])->name('edit');      // Form modifica
        Route::put('/{author}', [AuthorController::class, 'update'])->name('update');       // Aggiornamento autore
        Route::delete('/{author}', [AuthorController::class, 'destroy'])->name('destroy');  // Eliminazione autore
    });

    // ==========================
    // RICONOSCIMENTI
    // ==========================
    Route::prefix('admin/riconoscimenti')->name('riconoscimenti.')->group(function () {
        Route::get('/', [RiconoscimentoController::class, 'index'])->name('index');
        Route::get('/create', [RiconoscimentoController::class, 'create'])->name('create');
        Route::post('/', [RiconoscimentoController::class, 'store'])->name('store');
        Route::get('/{riconoscimento}/edit', [RiconoscimentoController::class, 'edit'])->name('edit');
        Route::put('/{riconoscimento}', [RiconoscimentoController::class, 'update'])->name('update');
        Route::delete('/{riconoscimento}', [RiconoscimentoController::class, 'destroy'])->name('destroy');
    });

    // ==========================
    // LOCATIONS
    // ==========================
    Route::prefix('admin/locations')->name('locations.')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::get('/{location}/edit', [LocationController::class, 'edit'])->name('edit');
        Route::put('/{location}', [LocationController::class, 'update'])->name('update');
        Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy');
    });
    
});
