<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SponsorshipController extends Controller
{
    // Mostra il form per creare una nuova sponsorizzazione
     public function index()
    {
        // Recupera tutti gli appartamenti dell'utente autenticato
        $user = Auth::user();
        $apartments = Apartment::where('user_id', $user->id)->with('sponsorships')->get();

        // Filtra solo gli appartamenti che hanno almeno una sponsorizzazione
        $sponsoredApartments = $apartments->filter(function ($apartment) {
            return $apartment->sponsorships->isNotEmpty();
        });

        return view('apartments.sponsorl.sponsor-index', ['apartments' => $sponsoredApartments]);
    }

    public function create(Apartment $apartment)
    {
        // Recupera tutti i pacchetti di sponsorizzazione disponibili
        $sponsorships = Sponsorship::all();

        // Recupera tutti gli appartamenti disponibili
        $apartments = Apartment::all();

        // Restituisce la vista con il form per creare una sponsorizzazione,
        // passando i dati relativi all'appartamento e ai pacchetti di sponsorizzazione
        return view('apartments.sponsorl.sponsor', compact('apartment', 'sponsorships', 'apartments'));
    }

    // Salva la sponsorizzazione
    public function store(Request $request)
    {
        // Trova l'appartamento specificato nel form tramite il suo ID
        $apartment = Apartment::findOrFail($request->apartment_id);

        // Controlla se l'appartamento è già sponsorizzato
        if ($this->isApartmentSponsored($apartment)) {
            // Se l'appartamento è già sponsorizzato, mostra un messaggio di errore
            return redirect()->back()->withErrors('Questo appartamento è già sponsorizzato.');
        }

        // Trova il pacchetto di sponsorizzazione specificato nel form tramite il suo ID
        $sponsorship = Sponsorship::findOrFail($request->sponsorship_id);

        // Effettua il redirect alla pagina di pagamento con i dettagli necessari
        return redirect()->route('payment.show', [
            'apartment_id' => $request->apartment_id,
            'sponsorship_id' => $request->sponsorship_id
        ]);
    }

    // Verifica se l'appartamento è già sponsorizzato
    private function isApartmentSponsored($apartment)
    {
        return $apartment->sponsorships()->exists();
    }

    // Mostra i dettagli della sponsorizzazione
    public function show($slug)
    {
        $apartment = Apartment::where('slug', $slug)->firstOrFail();
        $sponsorships = $apartment->sponsorships;
        return view('apartments.sponsorl.sponsor-show', compact('apartment', 'sponsorships'));
    }

    public function removeExpiredSponsorships()
    {
        $now = Carbon::now();
        Apartment::whereHas('sponsorships', function ($query) use ($now) {
            $query->where('end_sponsorship', '<', $now);
        })->each(function ($apartment) use ($now) {
            $apartment->sponsorships()->wherePivot('end_sponsorship', '<', $now)->detach();
        });

        return response()->json(['status' => 'success']);
    }
}




    
    