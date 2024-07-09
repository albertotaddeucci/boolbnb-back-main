<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AutocompleteController extends Controller
{
    public function autocompleteAddress()
    {


        $query = $_GET['query'];

        // Esegui la chiamata all'API esterna e recupera i risultati
        $res = file_get_contents('https://api.tomtom.com/search/2/geocode/' . Str::slug($query) . '.json?key=N4I4VUaeK36jrRC3vR5FfWqJS6fP6oTY&limit=3&countrySet=IT');

        // conversione del risultato json in un array associativo
        $res = json_decode($res, true);

        // Restituisci i risultati in formato JSON
        return response()->json([
            'succes' => true,
            'result' => $res

        ]);
    }
}
