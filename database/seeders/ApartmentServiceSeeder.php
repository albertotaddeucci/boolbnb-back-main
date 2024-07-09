<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Apartment::all() as $apartment) {

            $randomNum = rand(1, 6);

            $nRandomArray = [];

            for ($i = 1; $i <= $randomNum; $i++) {

                $randomId = rand(1, 6);

                if (!in_array($randomId, $nRandomArray)) {

                    array_push($nRandomArray, $randomId);

                    $service = Service::where('id', '=', $randomId)->first();

                    $apartment->services()->attach($service->id);

                    $apartment->save();
                }
            }
        }
    }
}
