<?php

namespace Tenolo\Apilyzer\Gateway;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Message\Authentication;
use JMS\Serializer\SerializerInterface;
use Tenolo\Apilyzer\Factory\DefaultSerializerFactory;

/**
 * Class Config
 *
 * @package Tenolo\Apilyzer\Gateway
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
abstract class Config implements ConfigInterface
{

    /** @var bool */
    protected $debugMode = false;

    /** @var Authentication */
    protected $authentication;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var HttpClient */
    protected $client;

    /**
     * @param Authentication           $authentication
     * @param SerializerInterface|null $serializer
     * @param HttpClient|null          $httpClient
     */
    public function __construct(
        Authentication $authentication = null,
        SerializerInterface $serializer = null,
        HttpClient $httpClient = null
    ) {
        $this->authentication = $authentication;
        $this->serializer = $serializer;
        $this->client = $httpClient;
        $this->debugMode = false;
    }

    /**
     * @inheritdoc
     */
    public function getAuthentication(): ?Authentication
    {
        return $this->authentication;
    }

    /**
     * @param Authentication $authentication
     */
    public function setAuthentication(Authentication $authentication): void
    {
        $this->authentication = $authentication;
    }

    /**
     * @inheritdoc
     */
    public function getSerializer(): SerializerInterface
    {
        if ($this->serializer === null) {
            $this->serializer = (new DefaultSerializerFactory())->create();
        }

        return $this->serializer;
    }

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    /**
     * @inheritdoc
     */
    public function getClient(): HttpClient
    {
        if ($this->client === null) {
            $this->client = HttpClientDiscovery::find();
        }

        return $this->client;
    }

    /**
     * @param HttpClient $client
     */
    public function setClient(HttpClient $client): void
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function isDebugMode(): bool
    {
        return $this->debugMode;
    }

    /**
     * @inheritdoc
     */
    public function setDebugMode(bool $debugMode): void
    {
        $this->debugMode = $debugMode;
    }

    /**
     * @inheritDoc
     */
    public function getDebugVarDumperDirectory(): ?string
    {
        return null;
    }
}
