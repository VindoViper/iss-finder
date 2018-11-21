<?php

namespace ISSFinderBundle\Client;

use GuzzleHttp\Exception\GuzzleException;

interface DataSourceClientInterface
{
    /**
     * @return array
     * @throws GuzzleException
     * @throws \Exception
     */
    public function getCurrentISSPosition();
}