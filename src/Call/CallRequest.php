<?php

namespace Tenolo\Apilyzer\Call;

use Http\Client\Common\Plugin;
use Http\Message\Authentication;
use Tenolo\Apilyzer\Collection\Collection;

/**
 * Class CallRequest
 *
 * @package Tenolo\Apilyzer\Call
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class CallRequest
{

    /** @var string */
    protected $endpoint;

    /** @var mixed */
    protected $body;

    /** @var Collection */
    protected $options;

    /** @var Collection */
    protected $headers;

    /** @var Collection */
    protected $plugins;

    /** @var Authentication|null */
    protected $authentication;

    /**
     * @param string              $endpoint
     * @param null                $body
     * @param array               $options
     * @param array               $headers
     * @param array               $plugins
     * @param Authentication|null $authentication
     */
    public function __construct(string $endpoint, $body = null, array $options = null, array $headers = null, array $plugins = null, ?Authentication $authentication = null)
    {
        $this->endpoint = $endpoint;
        $this->body = $body;
        $this->authentication = $authentication;

        $options = $options ?? [];
        $headers = $headers ?? [];
        $plugins = $plugins ?? [];

        $this->setOptions($options);
        $this->setHeaders($headers);
        $this->setPlugins($plugins);
    }

    /**
     * @param string $endpoint
     * @param null   $body
     * @param array  $options
     *
     * @return CallRequest
     */
    public static function create(string $endpoint, $body = null, array $options = []): CallRequest
    {
        return new static($endpoint, $body, $options);
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options->toArray();
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = []): void
    {
        $this->options = new Collection('mixed', $options);
    }

    /**
     * @param string $name
     * @param        $data
     */
    public function addOption(string $name, $data): void
    {
        $this->options->set($name, $data);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers->toArray();
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers = []): void
    {
        $this->headers = new Collection('mixed', $headers);
    }

    /**
     * @param string $name
     * @param        $data
     */
    public function addHeader(string $name, $data): void
    {
        $this->headers->set($name, $data);
    }

    /**
     * @return array
     */
    public function getPlugins(): array
    {
        return $this->plugins->toArray();
    }

    /**
     * @param array $plugins
     */
    public function setPlugins(array $plugins = []): void
    {
        $this->plugins = new Collection(Plugin::class, $plugins);
    }

    /**
     * @param string $name
     * @param Plugin $plugin
     */
    public function addPlugin(string $name, Plugin $plugin): void
    {
        $this->plugins->set($name, $plugin);
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
