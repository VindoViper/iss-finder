<?php

namespace ISSFinderBundle\Client;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use ISSFinderBundle\Client\DataSourceClientInterface;

class DataSourceClient implements DataSourceClientInterface
{
    const API_VERSION = 'v1';

    /**
     * @var ClientInterface
     */
    private $guzzle;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $satelliteId;

    /**
     * @param ClientInterface $guzzle
     * @param string $satelliteId
     * @param LoggerInterface $logger
     */
    public function __construct(
        ClientInterface $guzzle,
        LoggerInterface $logger,
        string $satelliteId
    ) {
        $this->guzzle = $guzzle;
        $this->logger = $logger;
        $this->satelliteId = $satelliteId;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentISSPosition() : array
    {
        $path = sprintf('/%s/satellites/%s', self::API_VERSION, $this->satelliteId);
        try {
            $response = $this->guzzle->request('GET', $path);
        } catch (GuzzleException $e) {
            $this->logger->error(sprintf("Error when calling ISS Data Source: %s", $e->getMessage()));
            throw $e;
        }

        return $this->decodeResponse($response);
    }

    /**
     * @param ResponseInterface $responseObject
     * @return array
     */
    private function decodeResponse(ResponseInterface $responseObject)
    {
        $body = $responseObject->getBody()->getContents();

        return json_decode($body, true);
    }
}