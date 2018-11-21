<?php

namespace ISSFinderBundle\Calculator;

use ISSFinderBundle\Calculator\CalculatorInterface;

abstract class CalculatorBase implements CalculatorInterface
{
    /**
     * {@inheritdoc}
     */
    abstract public function calculateDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2);

    /**
     * @param float $degree
     * @return float
     */
    protected function degreeToRadian(float $degree)
    {
        return $degree * (pi()/180);
    }
}