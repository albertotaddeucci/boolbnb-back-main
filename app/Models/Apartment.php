<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apartment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'n_rooms', 'n_beds', 'n_bathrooms', 'squared_meters', 'is_visible', 'description', 'address', 'latitude', 'longitude', 'user_id', 'slug'];

    public function sponsorships()
    {
       return $this->belongsToMany(Sponsorship::class)
        ->withPivot('start_sponsorship', 'end_sponsorship');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
