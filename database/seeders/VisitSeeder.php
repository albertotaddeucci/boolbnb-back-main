<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Visit;
use App\Models\Apartment;
use Faker\Factory as Faker;

class VisitSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $apartments = Apartment::all();
        $startDate = new \DateTime('2022-01-01');
        $endDate = new \DateTime('2024-04-30');

        foreach ($apartments as $apartment) {
            // Ensure one visit per month for each apartment
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $newVisit = new Visit();

                $timestamp = $faker->dateTimeBetween($currentDate->format('Y-m-01 00:00:00'), $currentDate->format('Y-m-t 23:59:59'));
                $ipAddress = $faker->ipv4;

                $newVisit->timestamp_visit = $timestamp;
                $newVisit->ip_address = $ipAddress;
                $newVisit->apartment_id = $apartment->id;

                $newVisit->save();

                // Move to the next month
                $currentDate->modify('first day of next month');
            }
        }

        // Add additional random visits
        for ($i = 0; $i < 40; $i++) {
            $newVisit = new Visit();

            $timestamp = $faker->dateTimeBetween('-2 years', 'now');
            $ipAddress = $faker->ipv4;

            $randomApartment = $apartments->random()->id;

            $newVisit->timestamp_visit = $timestamp;
            $newVisit->ip_address = $ipAddress;
            $newVisit->apartment_id = $randomApartment;

            $newVisit->save();
        }
    }
}
