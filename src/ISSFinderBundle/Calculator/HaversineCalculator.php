<?php

namespace ISSFinderBundle\Calculator;

use ISSFinderBundle\Calculator\CalculatorBase;

class HaversineCalculator extends CalculatorBase
{
    const EARTH_RADIUS_KM = 6371;

    /**
     * {@inheritdoc}
     */
    public function calculateDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2)
    {
        $degreesLatitude = $this->degreeToRadian($lat2 - $lat1);
        $degreesLongitude = $this->degreeToRadian($lon2 - $lon1);

        $angle = sin($degreesLatitude / 2) * sin($degreesLatitude / 2)
            + cos($this->degreeToRadian($lat1)) * cos($this->degreeToRadian($lat2))
            * sin($degreesLongitude / 2) * sin($degreesLongitude / 2);

        $centralAngle = 2 * atan2(sqrt($angle), sqrt(1 - $angle));
        $distance = self::EARTH_RADIUS_KM * $centralAngle;

        return round($distance, 4);
    }
}