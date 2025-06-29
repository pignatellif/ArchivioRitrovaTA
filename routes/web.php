<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\RiconoscimentoController;
use App\Http\Controllers\EventContentController;

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

// ==========================
// BLOCCA REGISTRAZIONE
// ==========================
Route::match(['get', 'post'], 'register', function () {
    abort(403, 'Registrazione disabilitata.');
});

// ==========================
// AREA ADMIN (Protetta - SOLO 'auth')
// ==========================
Route::middleware(['auth'])->group(function () {

    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Profilo admin
    Route::get('/admin/profile', [AdminController::class, 'showProfile'])->name('admin.profile');

    // Gestione 2FA (pagina gestione)
    Route::get('/admin/two-factor-authentication', [AdminController::class, 'showTwoFactor'])->name('admin.two-factor-authentication');

    Route::post('/admin/profile/update-email', [AdminController::class, 'updateEmail'])->name('admin.profile.updateEmail');
    Route::post('/admin/profile/update-password', [AdminController::class, 'updatePassword'])->name('admin.profile.updatePassword');

    // Gestione 2FA
    Route::get('/admin/twofactor', [AdminController::class, 'showTwoFactor'])->name('admin.twofactor');

    // ==========================
    // VIDEO
    // ==========================
    Route::prefix('admin/videos')->name('videos.')->group(function () {
        Route::get('/', [VideoController::class, 'index'])->name('index');
        Route::get('/create', [VideoController::class, 'create'])->name('create');
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
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        Route::delete('/{event}/remove-cover-image', [EventController::class, 'removeCoverImage'])->name('removeCoverImage');

        // === CONTENTS degli eventi ===
        Route::get('/{event}/contents/create', [EventContentController::class, 'create'])->name('contents.create');
        Route::post('/{event}/contents', [EventContentController::class, 'store'])->name('contents.store');
        Route::put('/{event}/contents/{content}', [EventContentController::class, 'update'])->name('contents.update');
        Route::delete('/{event}/contents/{content}', [EventContentController::class, 'destroy'])->name('contents.destroy');
        Route::post('/{event}/contents/reorder', [EventContentController::class, 'reorder'])->name('contents.reorder');
    });

    // ==========================
    // AUTORI
    // ==========================
    Route::prefix('admin/authors')->name('authors.')->group(function () {
        Route::get('/', [AuthorController::class, 'index'])->name('index');
        Route::get('/create', [AuthorController::class, 'create'])->name('create');
        Route::post('/', [AuthorController::class, 'store'])->name('store');
        Route::get('/{author}/edit', [AuthorController::class, 'edit'])->name('edit');
        Route::put('/{author}', [AuthorController::class, 'update'])->name('update');
        Route::delete('/{author}', [AuthorController::class, 'destroy'])->name('destroy');
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

});