<?php

namespace App\Http\Controllers;

use App\Models\Apartment;

use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Models\Service;
use App\Models\Sponsorship;
use App\Models\User;
use Braintree\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;



class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apartments = Apartment::where('user_id', '=', Auth::id())->get();

        return view('apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //prendiamo i servizi da db e le passiamo alla view
        $services = Service::all();

        return view('apartments.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApartmentRequest $request)
    {
        $request->validated();

        $newApartment = new Apartment();

        if ($request->hasFile('image')) {

            $path = Storage::disk('public')->put('bnb_images', $request->image);

            $newApartment->image = $path;
        }

        // chiamata API per la conversione dell'indirizzo in latitudine e longitudine
        $res = file_get_contents('https://api.tomtom.com/search/2/geocode/' . Str::slug($request->address) . '.json?key=N4I4VUaeK36jrRC3vR5FfWqJS6fP6oTY');
        // conversione del risultato json in un array associativo
        $res = json_decode($res, true);

        // inserimento della latitudine del nuovo appartamento
        $newApartment->latitude = $res['results'][0]['position']['lat'];

        // inserimento della longitudine del nuovo appartamento
        $newApartment->longitude = $res['results'][0]['position']['lon'];


        $newApartment->fill($request->all());

        $newApartment->slug = Str::slug($newApartment->title);


        //collegamento appartamento al'utente che si è loggato
        $newApartment->user_id = Auth::id();

        $newApartment->save();

        //inserimento dati in tabella ponte
        $newApartment->services()->attach($request->services);



        return redirect()->route('admin.apartments.show', $newApartment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Gateway $gateway, $slug)
    {
        // Trova l'appartamento utilizzando lo slug
        $apartment = Apartment::where('slug', $slug)->firstOrFail();

        // Controllo se l'utente è il proprietario dell'appartamento
        if ($apartment->user_id !== Auth::id()) {
            // Se l'utente non è il proprietario dell'appartamento, restituisci una risposta 404
            abort(404);
        }

        // Altrimenti, mostra la vista dell'appartamento
        $success = false;
        $sponsorships = Sponsorship::all();
        $clientToken = $gateway->clientToken()->generate();

        return view('apartments.show', compact('success', 'apartment', 'clientToken', 'sponsorships'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apartment $apartment)
    {
        //prendiamo i servizi da db e le passiamo alla view
        $services = Service::all();

        return view('apartments.edit', compact('apartment', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreApartmentRequest $request, Apartment $apartment)
    {
        $request->validated();


        if ($request->hasFile('image')) {

            $path = Storage::disk('public')->put('bnb_images', $request->image);

            $apartment->image = $path;
        }

        // chiamata API per la conversione dell'indirizzo in latitudine e longitudine
        $res = file_get_contents('https://api.tomtom.com/search/2/geocode/' . Str::slug($request->address) . '.json?key=N4I4VUaeK36jrRC3vR5FfWqJS6fP6oTY');
        // conversione del risultato json in un array associativo
        $res = json_decode($res, true);

        // modifica della latitudine del nuovo appartamento
        $apartment->latitude = $res['results'][0]['position']['lat'];

        // modifica della longitudine del nuovo appartamento
        $apartment->longitude = $res['results'][0]['position']['lon'];


        $apartment->slug = Str::slug($request->title);

        $apartment->update($request->all());


        //collegamento appartamento al'utente che si è loggato
        $apartment->user_id = Auth::id();

        $apartment->save();

        // modifichiamo i services collegati al apartment
        $apartment->services()->sync($request->services);



        return redirect()->route('admin.apartments.show', $apartment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apartment $apartment)
    {
        $apartment->delete();

        return redirect()->route('admin.apartments.index');
    }
}