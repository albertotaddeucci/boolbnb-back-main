<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;


class StatisticController extends Controller
{
    public function counter(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $visits = Visit::whereYear('timestamp_visit', $year)
            ->whereMonth('timestamp_visit', $month)
            ->select('apartment_id', DB::raw('count(*) as total_visits'))
            ->groupBy('apartment_id')
            ->get();

        return response()->json([
            'success' => true,
            'result' => $visits,
        ]);
    }

    public function getVisitsByApartmentId(Request $request, $apartmentId)
    {
        $year = $request->input('year');

        $visits = Visit::where('apartment_id', $apartmentId)
            ->whereYear('timestamp_visit', $year)
            ->select(DB::raw('MONTH(timestamp_visit) as month'), DB::raw('count(*) as total_visits'))
            ->groupBy(DB::raw('MONTH(timestamp_visit)'))
            ->get();

        return response()->json([
            'success' => true,
            'result' => $visits,
        ]);
    }
    public function store(Request $request)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        $existingVisit = Visit::where('ip_address', $request->ip)
            ->where('apartment_id', $request->apartment)
            ->whereYear('timestamp_visit', $currentYear)
            ->whereMonth('timestamp_visit', $currentMonth)
            ->exists();

        if (!$existingVisit) {
            $newVisit = new Visit();
            $newVisit->timestamp_visit = date('Y-m-d');
            $newVisit->ip_address = $request->ip;
            $newVisit->apartment_id = $request->apartment;
            $newVisit->save();

            return response()->json([
                'success' => true,
                'result' => 'Visita salvata con successo'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Ho già guardato questo appartamento'
            ]);
        }
    }
}
