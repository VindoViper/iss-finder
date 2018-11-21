<?php

namespace Tests\Unit\ISSFinderBundle\Client;

use PHPUnit\Framework\TestCase;
use ISSFinderBundle\Client\DataSourceClientInterface;
use ISSFinderBundle\Client\DataSourceClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;

class DataSourceClientTest extends TestCase
{
    const SATELLITE_ID = 25544;

    /**
     * @var array
     */
    private $responseBody = [
        'name'=> 'iss',
        'id'=> 25544,
        'latitude'=> 47.640584645123,
        'longitude'=> 83.800672354629,
        'altitude'=> 407.55816884442,
        'velocity'=> 27625.489169227,
        'visibility'=> 'eclipsed',
        'footprint'=> 4443.6154603421,
        'timestamp'=> 1542742784,
        'daynum'=> 2458443.3192593,
        'solar_lat'=> -19.808980134531,
        'solar_lon'=> 241.49289670078,
        'units'=> 'kilometers'
    ];

    /**
     * @var DataSourceClientInterface
     */
    private $client;

    public function setUp()
    {
        $this->client = new DataSourceClient(
            $this->mockGuzzleClient(),
            $this->mockLogger(),
            self::SATELLITE_ID);
    }

    public function testGetCurrentISSPositionOK()
    {
        $result = $this->client->getCurrentISSPosition();
        $this->assertEquals($result, $this->responseBody);
    }

    /**
     * @expectedException \GuzzleHttp\Exception\BadResponseException
     */
    public function testGetCurrentISSPositionError()
    {
        $this->client = new DataSourceClient(
            $this->mockGuzzleClient(true),
            $this->mockLogger(),
            self::SATELLITE_ID
        );
        $this->client->getCurrentISSPosition();
    }

    /**
     * @param bool $error
     * @return \GuzzleHttp\ClientInterface
     */
    private function mockGuzzleClient($error=false)
    {
        $path = '/v1/satellites/' . self::SATELLITE_ID;
        $mock = $this->getMockBuilder('GuzzleHttp\ClientInterface')
            ->disableOriginalConstructor()
            ->setMethods(['request', 'send', 'sendAsync', 'requestAsync', 'getConfig'])
            ->getMock()
            ;

        $response = new Response(200, [], json_encode($this->responseBody));

        if ($error) {
            $badRequest = new Request('GET', $path);
            $badResponse = new Response(500, [], "Unavailable");
            $mock->method('request')
                ->with('GET', $path)
                ->willThrowException(
                    new BadResponseException(
                    'Error response from server',
                        $badRequest,
                        $badResponse
                    )
                )
            ;
        } else {
            $mock->method('request')
                ->with('GET', $path)
                ->willReturn($response)
            ;
        }

        return $mock;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    private function mockLogger()
    {
        $mock = $this->getMockBuilder('Psr\Log\LoggerInterface')
            ->setMethods(['error', 'info', 'emergency', 'alert', 'critical', 'warning', 'notice', 'debug', 'log'])
            ->getMock()
            ;

        return $mock;
    }

}