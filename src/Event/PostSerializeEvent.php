<?php

namespace Tenolo\Apilyzer\Event;

use Symfony\Component\EventDispatcher\Event;
use Tenolo\Apilyzer\Call\Data;
use Tenolo\Apilyzer\Call\PreparedRequest;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class PostSerializeEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class PostSerializeEvent extends Event
{

    /** @var PreparedRequest */
    protected $preparedRequest;

    /**
     * @param PreparedRequest $preparedRequest
     */
    public function __construct(PreparedRequest $preparedRequest)
    {
        $this->preparedRequest = $preparedRequest;
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface
    {
        return $this->preparedRequest->getEndpoint();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->preparedRequest->getOptions();
    }

    /**
     * @return Data
     */
    public function getSubmittedData(): Data
    {
        return $this->preparedRequest->getBody();
    }
}
