<?php

namespace Tenolo\Apilyzer\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class ConfigureEndpointOptionsEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class ConfigureEndpointOptionsEvent extends Event
{

    /** @var EndpointInterface */
    protected $endpoint;

    /** @var OptionsResolver */
    protected $resolver;

    /** @var array */
    protected $options;

    /**
     * @param EndpointInterface $endpoint
     * @param OptionsResolver   $resolver
     * @param array             $options
     */
    public function __construct(EndpointInterface $endpoint, OptionsResolver $resolver, array $options = [])
    {
        $this->endpoint = $endpoint;
        $this->resolver = $resolver;
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
     * @return OptionsResolver
     */
    public function getResolver(): OptionsResolver
    {
        return $this->resolver;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasOption(string $name): bool
    {
        return isset($this->options[$name]);
    }

    /**
     * @param string $name
     * @param        $value
     */
    public function setOption(string $name, $value): void
    {
        $this->options[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function removeOption(string $name): void
    {
        if ($this->hasOption($name)) {
            unset($this->options[$name]);
        }
    }
}
