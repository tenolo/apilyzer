<?php

namespace Tenolo\Apilyzer\Call;

use Http\Client\Common\Plugin;
use Http\Message\Authentication;
use Tenolo\Apilyzer\Collection\Collection;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class PreparedRequest
 *
 * @package Tenolo\Apilyzer\Call
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class PreparedRequest
{

    /** @var EndpointInterface */
    protected $endpoint;

    /** @var array */
    protected $options;

    /** @var string */
    protected $method;

    /** @var string */
    protected $format;

    /** @var Data */
    protected $body;

    /** @var Collection */
    protected $routeParameters;

    /** @var Collection */
    protected $headers;

    /** @var Collection */
    protected $plugins;

    /** @var Authentication|null */
    protected $authentication;

    /**
     * @param EndpointInterface   $endpoint
     * @param array               $options
     * @param string              $method
     * @param                     $body
     * @param string              $format
     * @param array               $headers
     * @param array               $plugins
     * @param Authentication|null $authentication
     */
    public function __construct(
        EndpointInterface $endpoint,
        array $options,
        string $method,
        $body,
        string $format,
        array $headers,
        array $plugins,
        ?Authentication $authentication
    ) {
        $this->endpoint = $endpoint;
        $this->options = $options;
        $this->method = $method;
        $this->format = $format;
        $this->body = new Data($body);
        $this->headers = new Collection('mixed', $headers);
        $this->routeParameters = new Collection('scalar', $headers);
        $this->plugins = new Collection(Plugin::class, $plugins);
        $this->authentication = $authentication;
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface
    {
        return $this->endpoint;
    }

    /**
     * @param EndpointInterface $endpoint
     */
    public function setEndpoint(EndpointInterface $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    /**
     * @return Data
     */
    public function getBody(): Data
    {
        return $this->body;
    }

    /**
     * @param Data $body
     */
    public function setBody(Data $body): void
    {
        $this->body = $body;
    }

    /**
     * @return Collection
     */
    public function getRouteParameters(): Collection
    {
        return $this->routeParameters;
    }

    /**
     * @param Collection $routeParameters
     */
    public function setRouteParameters(Collection $routeParameters): void
    {
        $this->routeParameters = $routeParameters;
    }

    /**
     * @return Collection
     */
    public function getHeaders(): Collection
    {
        return $this->headers;
    }

    /**
     * @param Collection $headers
     */
    public function setHeaders(Collection $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return Collection
     */
    public function getPlugins(): Collection
    {
        return $this->plugins;
    }

    /**
     * @param Collection $plugins
     */
    public function setPlugins(Collection $plugins): void
    {
        $this->plugins = $plugins;
    }

    /**
     * @return Authentication|null
     */
    public function getAuthentication(): ?Authentication
    {
        return $this->authentication;
    }

    /**
     * @param Authentication|null $authentication
     */
    public function setAuthentication(?Authentication $authentication): void
    {
        $this->authentication = $authentication;
    }
}
