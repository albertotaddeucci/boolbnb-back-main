<?php

use App\Http\Controllers\Api\ApartmentController;
use App\Http\Controllers\Api\AutocompleteController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\FilterController;
use App\Http\Controllers\Api\StatisticController;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rotta per ottenere i dati dell'utente autenticato
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotta per l'autocompletamento degli indirizzi
Route::get('autocomplete-address', [AutocompleteController::class, 'autocompleteAddress']);

// Rotte per gestire gli appartamenti
Route::get('/apartments', [ApartmentController::class, 'index']);
Route::get('/apartments/{slug}', [ApartmentController::class, 'show']);
Route::get('/search', [ApartmentController::class, 'search']); // Rotta per la ricerca
Route::get('/filter', [FilterController::class, 'filter']); // Rotta per i filtri
Route::get('/service', [FilterController::class, 'service']); // Rotta per i filtri
Route::get('/service_filter', [FilterController::class, 'serviceFilter']); // Rotta per i filtri

// Rotta per prendere dati dal form front-end
Route::post('/new-contact', [LeadController::class, 'store']);

Route::get('/show-sponsored', [ApartmentController::class, 'showSponsored']); // Rotta per la visualizzazione delle sponsorizzate

Route::get('/all-sponsorship', [ApartmentController::class, 'sponsorship_apartments']);

Route::get('/visits', [StatisticController::class, 'counter']);

Route::get('/visits/{apartmentId}', [StatisticController::class, 'getVisitsByApartmentId']);

Route::post('/visits/store', [StatisticController::class, 'store']);

Route::get('/messages/{apartmentId}/{year}', [LeadController::class, 'getMessagesByApartmentIdAndYear']);
