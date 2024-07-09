<?php

namespace Database\Seeders;

use App\Models\Service;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = ['wi-fi', 'piscina', 'portineria', 'posto auto', 'sauna', 'vista mare'];

        $services = [
            [
                "name" => "Wi-fi",
                "icon" => "fa-solid fa-wifi"
            ],
            [
                "name" => "Piscina",
                "icon" => "fa-solid fa-water-ladder"
            ],
            [
                "name" => "Portineria",
                "icon" => "fa-solid fa-door-closed"
            ],
            [
                "name" => "Posto auto",
                "icon" => "fa-solid fa-car"
            ],
            [
                "name" => "Sauna",
                "icon" => "fa-solid fa-person-booth"
            ],
            [
                "name" => "Vista mare",
                "icon" => "fa-solid fa-water"
            ],
        ];

        foreach ($services as $service) {
            $newService = new Service();

            $newService->name = $service['name'];
            $newService->icon = $service['icon'];

            $newService->save();
        }
    }
}
