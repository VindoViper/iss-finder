<?php

namespace ISSFinderBundle\Service;

use ISSFinderBundle\Client\DataSourceClientInterface;
use ISSFinderBundle\Calculator\CalculatorInterface;

class ISSFinderAPI
{
    /**
     * @var DataSourceClientInterface $client
     */
    private $client;

    /**
     * @var CalculatorInterface
     */
    private $distanceCalculator;

    /**
     * @param DataSourceClientInterface $client
     * @param CalculatorInterface $distanceCalculator
     */
    public function __construct(DataSourceClientInterface $client, CalculatorInterface $distanceCalculator)
    {
        $this->client = $client;
        $this->distanceCalculator = $distanceCalculator;
    }

    /**
     * @return array
     */
    public function getCurrentISSPosition()
    {
        return $this->client->getCurrentISSPosition();
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @return float
     * @throws \Exception
     */
    public function getDistanceToISSInKilometers(float $latitude, float $longitude)
    {
        $currentPosition = $this->getCurrentISSPosition();
        if (!isset($currentPosition['latitude']) || !isset($currentPosition['longitude'])) {
            throw new \Exception("Incomplete data source response");
        }
        $ISSLatitude = $currentPosition['latitude'];
        $ISSLongitude = $currentPosition['longitude'];

        return $this->distanceCalculator->calculateDistanceKm($latitude, $longitude, $ISSLatitude, $ISSLongitude);
    }
}