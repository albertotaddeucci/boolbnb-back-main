<?php

namespace Database\Seeders;


use App\Models\Sponsorship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sponsorships=[
            [
                'title'=>'Standard',
                'duration'=>'24:00:00',
                'price'=>2.99,
                'description'=>"L'appartamento sponsorizzato appare per 24h in Homepage nella sezione “Appartamenti in Evidenza” e nella pagina di ricerca, viene posizionato sempre prima di un appartamento non sponsorizzato che soddisfa le stesse caratteristiche di ricerca."
            ],
            [
                'title'=>'Plus',
                'duration'=>'48:00:00',
                'price'=>5.99,
                'description'=>"L'appartamento sponsorizzato appare per 48h in Homepage nella sezione “Appartamenti in Evidenza” e nella pagina di ricerca, viene posizionato sempre prima di un appartamento non sponsorizzato che soddisfa le stesse caratteristiche di ricerca."
            ],
            [
                'title'=>'Premium',
                'duration'=>'144:00:00',
                'price'=>9.99,
                'description'=>"L'appartamento sponsorizzato appare per 144h in Homepage nella sezione “Appartamenti in Evidenza” e nella pagina di ricerca, viene posizionato sempre prima di un appartamento non sponsorizzato che soddisfa le stesse caratteristiche di ricerca."
            ]
        ];

        foreach($sponsorships as $sponsorship){
            $newSponsor = new Sponsorship();

            $newSponsor->title = $sponsorship['title'];
            $newSponsor->h_duration = $sponsorship['duration'];
            $newSponsor->price = $sponsorship['price'];
            $newSponsor->description = $sponsorship['description'];
            $newSponsor->save();
            
        }
    }
}
