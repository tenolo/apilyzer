<?php

namespace Tenolo\Apilyzer\Event;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\Event;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class PreDeserializeEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class PreDeserializeEvent extends Event
{

    /** @var EndpointInterface */
    protected $endpoint;

    /** @var ResponseInterface */
    protected $response;

    /** @var string */
    protected $receivedData;

    /** @var string */
    protected $deserializationType;

    /** @var string */
    protected $deserializationFormat;

    /** @var bool */
    protected $supportsDeserialization;

    /**
     * @param EndpointInterface $endpoint
     * @param ResponseInterface $response
     * @param string|null       $receivedData
     * @param string|null       $deserializationType
     * @param string|null       $deserializationFormat
     * @param bool              $supportsDeserialization
     */
    public function __construct(
        EndpointInterface $endpoint,
        ResponseInterface $response,
        ?string $receivedData,
        ?string $deserializationType,
        ?string $deserializationFormat,
        bool $supportsDeserialization
    ) {
        $this->endpoint = $endpoint;
        $this->response = $response;
        $this->receivedData = $receivedData;
        $this->deserializationType = $deserializationType;
        $this->deserializationFormat = $deserializationFormat;
        $this->supportsDeserialization = $supportsDeserialization;
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
     * @return string
     */
    public function getReceivedData(): string
    {
        return $this->receivedData;
    }

    /**
     * @param string $receivedData
     */
    public function setReceivedData(string $receivedData): void
    {
        $this->receivedData = $receivedData;
    }

    /**
     * @return string|null
     */
    public function getDeserializationType(): ?string
    {
        return $this->deserializationType;
    }

    /**
     * @param string $deserializationType
     */
    public function setDeserializationType(string $deserializationType): void
    {
        $this->deserializationType = $deserializationType;
    }

    /**
     * @return string|null
     */
    public function getDeserializationFormat(): ?string
    {
        return $this->deserializationFormat;
    }

    /**
     * @param string $deserializationFormat
     */
    public function setDeserializationFormat(string $deserializationFormat): void
    {
        $this->deserializationFormat = $deserializationFormat;
    }

    /**
     * @return bool
     */
    public function isSupportsDeserialization(): bool
    {
        return $this->supportsDeserialization;
    }

    /**
     * @param bool $supportsDeserialization
     */
    public function setSupportsDeserialization(bool $supportsDeserialization): void
    {
        $this->supportsDeserialization = $supportsDeserialization;
    }
}
