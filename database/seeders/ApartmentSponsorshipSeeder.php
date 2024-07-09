<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApartmentSponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                'apartment_id' => 1,
                'sponsorship_id' => 2,
                'start_sponsorship' => '2024-10-03T13:22:00',
                'end_sponsorship' => '2025-11-03T13:22:00'
            ],
            [
                'apartment_id' => 2,
                'sponsorship_id' => 1,
                'start_sponsorship' => '2021-10-03T13:22:00',
                'end_sponsorship' => '2025-11-05T13:22:00'
            ],
            [
                'apartment_id' => 3,
                'sponsorship_id' => 3,
                'start_sponsorship' => '2021-10-03T13:22:00',
                'end_sponsorship' => '2025-11-05T13:22:00'
            ],
        ];

        DB::table('apartment_sponsorship')->insert($data);
    }
}
