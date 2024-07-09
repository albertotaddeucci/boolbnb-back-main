<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Apartment;
use Faker\Factory as Faker;

class LeadSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $apartments = Apartment::all();
        $startDate = new \DateTime('2022-01-01');
        $endDate = new \DateTime('2024-04-30');

        foreach ($apartments as $apartment) {

            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $newLead = new Lead();

                $newLead->name = $faker->firstName;
                $newLead->surname = $faker->lastName;
                $newLead->mail_address = $faker->unique()->safeEmail;
                $newLead->message = $faker->sentence;
                $newLead->apartment_id = $apartment->id;
                $newLead->created_at = $currentDate->format('Y-m-d H:i:s');
                $newLead->updated_at = $currentDate->format('Y-m-d H:i:s');

                $newLead->save();

                $currentDate->modify('first day of next month');
            }

            for ($i = 0; $i < 8; $i++) {
                $newLead = new Lead();

                $newLead->name = $faker->firstName;
                $newLead->surname = $faker->lastName;
                $newLead->mail_address = $faker->unique()->safeEmail;
                $newLead->message = $faker->sentence;
                $newLead->apartment_id = $apartment->id;
                $newLead->created_at = $faker->dateTimeBetween('2022-01-01', 'now');
                $newLead->updated_at = $faker->dateTimeBetween($newLead->created_at, 'now');

                $newLead->save();
            }
        }
    }
}
