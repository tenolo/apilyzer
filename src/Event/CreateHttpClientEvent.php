<?php

namespace Tenolo\Apilyzer\Event;

use Http\Client\Common\Plugin;
use Http\Client\HttpClient;
use Tenolo\Apilyzer\Collection\CollectionInterface;
use Symfony\Component\EventDispatcher\Event;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class CreateHttpClientEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class CreateHttpClientEvent extends Event
{

    /** @var EndpointInterface */
    protected $endpoint;

    /** @var HttpClient */
    protected $client;

    /** @var CollectionInterface|Plugin[] */
    protected $plugins;

    /**
     * @param EndpointInterface   $endpoint
     * @param HttpClient          $client
     * @param CollectionInterface $plugins
     */
    public function __construct(EndpointInterface $endpoint, HttpClient $client, CollectionInterface $plugins)
    {
        $this->endpoint = $endpoint;
        $this->client = $client;
        $this->plugins = $plugins;
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface
    {
        return $this->endpoint;
    }

    /**
     * @return HttpClient
     */
    public function getClient(): HttpClient
    {
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
     * @return CollectionInterface
     */
    public function getPlugins(): CollectionInterface
    {
        return $this->plugins;
    }

    /**
     * @param Plugin $plugin
     */
    public function removePlugin(Plugin $plugin): void
    {
        if ($this->plugins->contains($plugin)) {
            $this->plugins->remove($plugin);
        }
    }

    /**
     * @param Plugin $plugin
     */
    public function addPlugin(Plugin $plugin): void
    {
        if (!$this->plugins->contains($plugin)) {
            $this->plugins->add($plugin);
        }
    }
}
