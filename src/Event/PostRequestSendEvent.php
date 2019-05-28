<?php

namespace Tenolo\Apilyzer\Event;

use Symfony\Component\EventDispatcher\Event;
use Tenolo\Apilyzer\Call\CallInterface;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class PostRequestSendEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class PostRequestSendEvent extends Event
{

    /** @var EndpointInterface */
    protected $endpoint;

    /** @var string */
    protected $method;

    /** @var string */
    protected $gatewayUrl;

    /** @var CallInterface */
    protected $call;

    /**
     * @param EndpointInterface $endpoint
     * @param                   $method
     * @param                   $gatewayUrl
     * @param CallInterface     $call
     */
    public function __construct(EndpointInterface $endpoint, $method, $gatewayUrl, CallInterface $call)
    {
        $this->endpoint = $endpoint;
        $this->method = $method;
        $this->gatewayUrl = $gatewayUrl;
        $this->call = $call;
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getGatewayUrl(): string
    {
        return $this->gatewayUrl;
    }

    /**
     * @return CallInterface
     */
    public function getCall(): CallInterface
    {
        return $this->call;
    }
}
