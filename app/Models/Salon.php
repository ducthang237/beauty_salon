<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'salon_name', 'address', 'phone', 'city_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function technicals()
    {
        return $this->hasMany(Technical::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
