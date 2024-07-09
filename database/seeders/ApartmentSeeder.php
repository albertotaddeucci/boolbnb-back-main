<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $apartments = config('apartments');

        foreach ($apartments as $apartment) {

            $newApartment = new Apartment();

            $newApartment->title = $apartment['title'];
            $newApartment->n_rooms = $apartment['n_rooms'];
            $newApartment->n_beds = $apartment['n_beds'];
            $newApartment->n_bathrooms = $apartment['n_bathrooms'];
            $newApartment->squared_meters = $apartment['squared_meters'];
            $newApartment->image = $apartment['image'];
            $newApartment->description = $apartment['description'];
            $newApartment->address = $apartment['address'];
            $newApartment->latitude = $apartment['latitude'];
            $newApartment->longitude = $apartment['longitude'];
            $newApartment->slug = $apartment['slug'];
            $newApartment->user_id = $apartment['user_id'];



            $newApartment->save();
        }
    }
}
