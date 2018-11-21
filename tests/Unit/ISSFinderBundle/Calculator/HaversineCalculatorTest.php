<?php

namespace Tests\Unit\ISSFinderBundle\Calculator;

use PHPUnit\Framework\TestCase;
use ISSFinderBundle\Calculator\CalculatorInterface;
use ISSFinderBundle\Calculator\HaversineCalculator;

class HaversineCalculatorTest extends TestCase
{
    /**
     * @var CalculatorInterface
     */
    private $calculator;

    public function setUp()
    {
        $this->calculator = new HaversineCalculator();
    }

    public function testCalculateDistanceKm()
    {
        $result = $this->calculator->calculateDistanceKm(49.528379727168, 76.542756981661, 47.640584645123, 83.800672354629);
        $this->assertEquals(573.3607, $result);
    }
}