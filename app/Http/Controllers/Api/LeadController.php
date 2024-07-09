<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewContact;
use App\Models\Lead;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function store(Request $request)
    {

        // validazioni
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'mail_address' => 'required|email',
            'message' => 'required',


            // test
            'apartment_id' => 'nullable'

        ], [
            'name.required' => "Devi inserire il tuo nome",
            'surname.required' => "Devi inserire il tuo cognome",
            'mail_address.required' => "Devi inserire la tua email",
            'mail_address.email' => "Devi inserire una email corretta",
            'message.required' => "Devi inserire un messaggio",
        ]);


        // validazione non di successo
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }


        // salvataggio nel db
        $newLead = new Lead();
        $newLead->fill($request->all());
        $newLead->save();


        // invio della mail
        Mail::to('pool.gutierrezv@gmail.com')->send(new NewContact($newLead));


        // risposta client

        //restituiamo json con success true
        return response()->json([
            'success' => true,
            'request' => $request->all(),
        ]);
    }

    public function getMessagesByApartmentIdAndYear($apartmentId, $year)
    {
        // Verifica se l'appartamento esiste nel database
        $apartment = Apartment::find($apartmentId);
        if (!$apartment) {
            return response()->json([
                'success' => false,
                'error' => 'L\'appartamento non esiste.',
            ], 404);
        }

        // Ottieni i dati dei messaggi per l'appartamento e l'anno specificati
        $messages = Lead::where('apartment_id', $apartmentId)
            ->whereYear('created_at', $year)
            ->get();

        // Raggruppa i messaggi per mese
        $messagesByMonth = $messages->groupBy(function ($message) {
            return $message->created_at->format('m'); // Restituisce solo il mese (es. '01' per gennaio)
        });

        // Conta il numero di messaggi per ogni mese
        $messageCounts = $messagesByMonth->map(function ($messages) {
            return $messages->count();
        });

        // Assicura che ogni mese abbia un valore, anche se 0
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = substr('0' . $i, -2); // Assicura che il mese sia in formato 'MM'
            $result[$month] = $messageCounts->get($month, 0);
        }

        // Restituisci i dati dei messaggi
        return response()->json([
            'success' => true,
            'result' => $result,
        ]);
    }
}
