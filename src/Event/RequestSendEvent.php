<?php

namespace Tenolo\Apilyzer\Event;

use Psr\Http\Message\RequestInterface;
use Symfony\Component\EventDispatcher\Event;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class RequestSendEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class RequestSendEvent extends Event
{

    /** @var EndpointInterface */
    protected $endpoint;

    /** @var RequestInterface */
    protected $request;

    /** @var array */
    protected $options = [];

    /**
     * @param EndpointInterface $endpoint
     * @param RequestInterface  $request
     * @param array             $options
     */
    public function __construct(EndpointInterface $endpoint, RequestInterface $request, array $options = [])
    {
        $this->endpoint = $endpoint;
        $this->request = $request;
        $this->options = $options;
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface
    {
        return $this->endpoint;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
