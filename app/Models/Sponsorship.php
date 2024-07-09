<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    use HasFactory;

    protected $fillable=['title','h_duration','price','description'];

    public function apartemets(){
        return $this->belongsToMany(Apartment::class)
        ->withPivot('start_sponsorship', 'end_sponsorship');    }
}
