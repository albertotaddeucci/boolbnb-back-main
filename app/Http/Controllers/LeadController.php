<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Lead;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

class LeadController extends Controller
{
    public function index($id)
    {
        $slug = Apartment::find($id);
        $slug = $slug->slug;

        $leads = Lead::where('apartment_id', '=', $id)
            ->orderBy('created_at', 'DESC')
            ->get();
        // $leads = Lead::all();

        return view('leads.index', compact('leads', 'slug'));
    }

    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
    }
}
