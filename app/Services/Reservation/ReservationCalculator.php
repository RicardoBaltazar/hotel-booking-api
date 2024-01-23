<?php

namespace App\Services\Reservation;

class ReservationCalculator
{
    public function calculateTotalCost(float $price, int $daily_rates): float
    {
        return $price * $daily_rates;
    }
}
