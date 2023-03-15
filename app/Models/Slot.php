<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasAppointmentNumber;

class Slot extends Model
{
    use HasFactory, HasAppointmentNumber;

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
