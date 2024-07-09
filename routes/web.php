<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\SponsorshipController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/errors.404', function () {
    return view('errors.404');
})->middleware(['auth', 'verified'])->name('errors.404');

require __DIR__ . '/auth.php';
Route::get('/leads/{id}', [LeadController::class, 'index'])->name('leads.index');

Route::middleware(['auth', 'verified'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::resource('apartments', ApartmentController::class)->parameters(['apartments' => 'apartment:slug']);
        Route::get('/leads/{id}', [LeadController::class, 'index'])->name('leads.index');


        // Definisci le rotte per le sponsorizzazioni
        Route::get('/sponsorships', [SponsorshipController::class, 'index'])->name('sponsor.index');
        Route::get('sponsorship/create/{apartment:slug}', [SponsorshipController::class, 'create'])->name('sponsor.create');
        Route::post('sponsorship/store/{apartment:slug}', [SponsorshipController::class, 'store'])->name('sponsor.store');
        Route::get('sponsorship/show/{apartment:slug}', [SponsorshipController::class, 'show'])->name('sponsor.show');
    });
Route::get('/remove-expired-sponsorships', [SponsorshipController::class, 'removeExpiredSponsorships']);

Route::get('payment/form', [PaymentController::class, 'show'])->name('payment.form');
Route::post('payment/process', [PaymentController::class, 'process'])->name('payment.process');
Route::get('payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/show', [PaymentController::class, 'show'])->name('payment.show');

// Route::middleware(['auth', 'verified'])->group(function () {
// });
