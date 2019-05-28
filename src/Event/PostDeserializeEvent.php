<?php

namespace Tenolo\Apilyzer\Event;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\Event;
use Tenolo\Apilyzer\Call\Data;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class PostDeserializeEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class PostDeserializeEvent extends Event
{

    /** @var EndpointInterface */
    protected $endpoint;

    /** @var ResponseInterface */
    protected $response;

    /** @var Data */
    protected $receivedData;

    /**
     * @param EndpointInterface $endpoint
     * @param ResponseInterface $response
     * @param Data        $receivedData
     */
    public function __construct(
        EndpointInterface $endpoint,
        ResponseInterface $response,
        Data $receivedData
    ) {
        $this->endpoint = $endpoint;
        $this->response = $response;
        $this->receivedData = $receivedData;
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface
    {
        return $this->endpoint;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return Data
     */
    public function getReceivedData(): Data
    {
        return $this->receivedData;
    }
}
