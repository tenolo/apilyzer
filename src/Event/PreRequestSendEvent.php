<?php

namespace Tenolo\Apilyzer\Event;

use Http\Client\Common\Plugin;
use Http\Message\Authentication;
use Ramsey\Collection\CollectionInterface;
use Symfony\Component\EventDispatcher\Event;
use Tenolo\Apilyzer\Call\PreparedRequest;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class PreRequestSendEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class PreRequestSendEvent extends Event
{

    /** @var PreparedRequest */
    protected $preparedRequest;

    /** @var string */
    protected $gatewayUrl;

    /**
     * @param PreparedRequest $preparedRequest
     * @param string          $gatewayUrl
     */
    public function __construct(
        PreparedRequest $preparedRequest,
        string $gatewayUrl
    ) {
        $this->preparedRequest = $preparedRequest;
        $this->gatewayUrl = $gatewayUrl;
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface
    {
        return $this->preparedRequest->getEndpoint();
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->preparedRequest->getMethod();
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->preparedRequest->setMethod($method);
    }

    /**
     * @return string
     */
    public function getGatewayUrl(): string
    {
        return $this->gatewayUrl;
    }

    /**
     * @param string $gatewayUrl
     */
    public function setGatewayUrl(string $gatewayUrl): void
    {
        $this->gatewayUrl = $gatewayUrl;
    }

    /**
     * @return CollectionInterface
     */
    public function getPlugins(): CollectionInterface
    {
        return $this->preparedRequest->getPlugins();
    }

    /**
     * @param Plugin $plugin
     */
    public function addPlugin(Plugin $plugin): void
    {
        $this->getPlugins()->add($plugin);
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->preparedRequest->getBody();
    }

    /**
     * @return CollectionInterface
     */
    public function getHeaders(): CollectionInterface
    {
        return $this->preparedRequest->getHeaders();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return $this->getHeaders()->offsetExists($name);
    }

    /**
     * @param string $name
     * @param        $value
     */
    public function setHeader(string $name, $value): void
    {
        $this->getHeaders()->offsetSet($name, $value);
    }

    /**
     * @param string $name
     */
    public function removeHeader(string $name): void
    {
        if ($this->hasHeader($name)) {
            $this->getHeaders()->offsetUnset($name);
        }
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->preparedRequest->getOptions();
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getOption(string $name)
    {
        if (!$this->hasOption($name)) {
            return null;
        }

        return $this->preparedRequest->getOptions()[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasOption(string $name): bool
    {
        return isset($this->preparedRequest->getOptions()[$name]);
    }

    /**
     * @return Authentication
     */
    public function getAuthentication(): Authentication
    {
        return $this->preparedRequest->getAuthentication();
    }

    /**
     * @param Authentication $authentication
     */
    public function setAuthentication(Authentication $authentication): void
    {
        $this->preparedRequest->setAuthentication($authentication);
    }
}
