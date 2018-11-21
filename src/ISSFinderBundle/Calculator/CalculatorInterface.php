<?php

namespace ISSFinderBundle\Calculator;

interface CalculatorInterface
{
    /**
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    public function calculateDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2);
}