<?php

namespace Tests\Unit\ISSFinderBundle\Service;

use ISSFinderBundle\Calculator\HaversineCalculator;
use PHPUnit\Framework\TestCase;
use ISSFinderBundle\Service\ISSFinderAPI;
use ISSFinderBundle\Client\DataSourceClientInterface;
use ISSFinderBundle\Calculator\CalculatorInterface;

class ISSFinderApiTest extends TestCase
{
    const CORRECT_DISTANCE = 7522.8123;

    const TEST_LAT = 39.926358313224;

    const TEST_LON = 132.92340389968;

    const ISS_LAT = -12.249288801244;

    const ISS_LON = 86.476350031936;

    /**
     * @var ISSFinderAPI
     */
    private $ISSFinderApi;

    /**
     * @var array
     */
    private $currentPositionResponse;

    public function setUp()
    {
        $this->currentPositionResponse = [
            "name" => "iss",
            "id" => 25544,
            "latitude" => self::ISS_LAT,
            "longitude" => self::ISS_LON,
            "altitude" => 407.94155042166,
            "velocity" => 27600.176207572,
            "visibility" => "daylight",
            "footprint" => 4445.5995123183,
            "timestamp" => 1542796411,
            "daynum" => 2458443.9399421,
            "solar_lat" => -19.946469411422,
            "solar_lon" => 18.085453151466,
            "units" => "kilometers"
        ];

        $this->ISSFinderApi = new ISSFinderAPI(
            $this->mockDataSourceClient(),
            $this->mockCalculator()
        );
    }

    public function testGetCurrentISSPosition()
    {
        $result = $this->ISSFinderApi->getCurrentISSPosition();
        $this->assertEquals($this->currentPositionResponse, $result);
    }

    public function testgetDistanceToISSInKilometersOK()
    {
        $result = $this->ISSFinderApi->getDistanceToISSInKilometers(self::TEST_LAT, self::TEST_LON);
        $this->assertEquals(self::CORRECT_DISTANCE, $result);
    }

    /**
     * @expectedException \Exception
     */
    public function testgetDistanceToISSInKilometersError()
    {
        unset($this->currentPositionResponse['latitude']);
        $this->ISSFinderApi = new ISSFinderAPI(
            $this->mockDataSourceClient(),
            $this->mockCalculator()
        );

        $this->ISSFinderApi->getDistanceToISSInKilometers(self::TEST_LAT, self::TEST_LON);
    }

    /**
     * @return DataSourceClientInterface
     */
    private function mockDataSourceClient()
    {
        $mock = $this->getMockBuilder('ISSFinderBundle\Client\DataSourceClientInterface')
            ->setMethods(['getCurrentISSPosition'])
            ->getMock()
        ;

        $mock->method('getCurrentISSPosition')
            ->willReturn($this->currentPositionResponse)
        ;

        return $mock;
    }

    /**
     * @return CalculatorInterface
     */
    private function mockCalculator()
    {
        $mock = $this->getMockBuilder('ISSFinderBundle\Calculator\CalculatorInterface')
            ->setMethods(['calculateDistanceKm'])
            ->getMock()
        ;

        $mock->method('calculateDistanceKm')
            ->with(self::TEST_LAT, self::TEST_LON, self::ISS_LAT, self::ISS_LON)
            ->willReturn(self::CORRECT_DISTANCE)
        ;

        return $mock;
    }
}