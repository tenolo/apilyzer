<?php

namespace Tenolo\Apilyzer\Event;

use Symfony\Component\EventDispatcher\Event;
use Tenolo\Apilyzer\Call\Data;
use Tenolo\Apilyzer\Call\PreparedRequest;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class PreSerializeEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class PreSerializeEvent extends Event
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
     * @return string
     */
    public function getFormat(): string
    {
        return $this->preparedRequest->getFormat();
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->preparedRequest->setFormat($format);
    }

    /**
     * @return Data
     */
    public function getBody()
    {
        return $this->preparedRequest->getBody();
    }
}
