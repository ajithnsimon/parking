<?php

namespace App\Traits;

trait CalculatesParkingFee
{
    public function calculateFee($start_time, $end_time)
    {
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        $duration_minutes = round(($end_time - $start_time) / 60);

        if ($duration_minutes <= 150) {
            return 10;
        } else {
            $duration_minutes;
            $duration_hours = ceil($duration_minutes / 60);
            $fee = 10 + (($duration_hours - 2) * 10);
            $additional_minutes = $duration_minutes - 150 - (($duration_hours - 2) * 60);
            $additional_fee = ceil($additional_minutes / 30) * 5;
            $fee += $additional_fee;
            
            $start_date = date('Y-m-d', $start_time);
            $end_date = date('Y-m-d', $end_time);
            $overnight_days = floor((strtotime($end_date) - strtotime($start_date)) / 86400);
            if ($overnight_days > 0) {
                $fee += $overnight_days * 100;
            } else {
                $fee += 0;
            }

            return $fee;
        }
    }
}