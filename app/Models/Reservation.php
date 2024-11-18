<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rentee()
    {
        return $this->belongsTo(Rentee::class);
    }


    // Relationship with ItemsTransaction
    public function propertyReservation()
    {
        return $this->hasMany(PropertyReservation::class);
    }

}
