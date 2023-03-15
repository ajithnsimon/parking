<?php

namespace App\Traits;
use App\Models\Booking;

trait HasAppointmentNumber
{
    function getNextAppointmentNumber(string $current_Sequence): string
    {
        $sequence_number = ord($current_Sequence[0]) * 26 * 26 + ord($current_Sequence[1]) * 26 + ord($current_Sequence[2]);

        $sequence_number++;

        $sequence = '';
        $sequence .= chr(intdiv($sequence_number, 26 * 26) + 65);
        $sequence .= chr(intdiv($sequence_number % (26 * 26), 26) + 65);
        $sequence .= chr($sequence_number % 26 + 65);

        return strtoupper($sequence);
    }

    function getNextSlotNumber($slot_id)
    {
        $last_booking = Booking::where('slot_id', $slot_id)
            ->orderBy('appointment_number', 'desc')
            ->first();

        if (!$last_booking) {
            return 'AAA';
        }
        
        $last_sequence = substr($last_booking->appointment_number, 3);
        return self::getNextAppointmentNumber($last_sequence);
    }
}