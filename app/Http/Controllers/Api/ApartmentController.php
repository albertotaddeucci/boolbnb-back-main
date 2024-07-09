<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Sponsorship;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    // Metodo per ottenere tutti gli appartamenti
    public function index()
    {
        $apartments = Apartment::all();
        return response()->json([
            "success" => true,
            "results" => $apartments
        ]);
    }

    // Metodo per ottenere un appartamento specifico tramite slug
    public function show($slug)
    {
        $apartment = Apartment::selectRaw('apartments.*, users.name as user_name, users.surname as user_surname')->join('users', 'users.id', '=', 'apartments.user_id')->with(['sponsorships', 'services'])->where('slug', $slug)->firstOrFail();
        return response()->json([
            "success" => true,
            "apartment" => $apartment
        ]);
    }

    // Metodo per cercare appartamenti in base alla posizione
    public function search(Request $request)
    {
        // Ottenere latitudine e longitudine dalla richiesta
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Query per trovare appartamenti entro un certo raggio utilizzando la formula di Haversine
        $apartments = Apartment::selectRaw("*, (
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )
            ) AS distance", [$latitude, $longitude, $latitude])
            ->join('apartment_sponsorship', 'id', '=', 'apartment_sponsorship.apartment_id', 'left outer')
            ->having('distance', '<', 20)
            ->orderByRaw('-apartment_sponsorship.apartment_id DESC')  //Seleziona solo gli appartameti in cui il valore della colonna distance è inferiore al valore del raggio specificato dall'utente.
            ->orderBy('distance')
            ->get();

        // Restituisce i risultati della ricerca
        return response()->json([
            "success" => true,
            "results" => $apartments
        ]);
    }

    public function showSponsored(Request $request)
    {

        $sponsorships = [];

        foreach (Sponsorship::all() as $sponsorship) {
            array_push($sponsorships, $sponsorship['id']);
        }

        // Ottenere latitudine e longitudine dalla richiesta
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Query per trovare appartamenti entro un certo raggio utilizzando la formula di Haversine
        $apartments = Apartment::selectRaw("*, (
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )
            ) AS distance", [$latitude, $longitude, $latitude])
            ->join('apartment_sponsorship', 'id', '=', 'apartment_sponsorship.apartment_id')
            ->having('distance', '<', 30)  //Seleziona solo gli appartameti in cui il valore della colonna distance è inferiore al valore del raggio specificato dall'utente.
            ->orderBy('distance')
            ->when($sponsorships, function ($query, $sponsorships) {
                // Condizioni per garantire che tutti i servizi selezionati siano presenti
                $query->whereHas('sponsorships', function ($subQuery) use ($sponsorships) {
                    $subQuery->whereIn('id', $sponsorships);
                });
            })
            ->get();

        // Restituisce i risultati della ricerca
        return response()->json([
            "success" => true,
            "results" => $apartments,
        ]);
    }


    public function sponsorship_apartments(){

        $apartments = Apartment::select()
        ->join('apartment_sponsorship', 'id', '=', 'apartment_sponsorship.apartment_id')
        ->where('apartment_sponsorship.apartment_id','<>', null)
        ->orderBy('id')
        ->get();

        // Restituisce i risultati della ricerca
        return response()->json([
            "success" => true,
            "results" => $apartments,
        ]);

    }
}
