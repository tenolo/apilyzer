<?php

namespace Tenolo\Apilyzer\Manager;

use ReflectionClass;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;
use Tenolo\Apilyzer\Factory\DefaultEndpointFactory;
use Tenolo\Apilyzer\Factory\EndpointFactoryInterface;
use Tenolo\Utilities\Exception\InvalidArgumentTypeException;

/**
 * Class AbstractEndpointManager
 *
 * @package Tenolo\o2\TPI\Manager
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
abstract class AbstractEndpointManager implements EndpointManagerInterface
{

    /** @var string[]|EndpointInterface[] */
    protected $endpoints = [];

    /** @var EndpointFactoryInterface */
    protected $factory;

    /**
     *
     */
    public function __construct()
    {
        $this->load();
    }

    /**
     *
     */
    protected function load(): void
    {
        // nothing to do in default
    }

    /**
     * @return string[]|EndpointInterface[]
     */
    protected function getEndpoints(): array
    {
        return $this->endpoints;
    }

    /**
     * @inheritdoc
     */
    public function getEndpointsAsArray(): array
    {
        return $this->endpoints;
    }

    /**
     * @inheritdoc
     */
    public function hasEndpoint($name): bool
    {
        return isset($this->endpoints[$name]);
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint($name)
    {
        if (!$this->hasEndpoint($name)) {
            throw new \RuntimeException('endpoint "'.$name.'" doesnt exist');
        }

        return $this->endpoints[$name];
    }

    /**
     * @inheritdoc
     */
    public function addEndpoint($class)
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->implementsInterface(EndpointInterface::class)) {
            throw new InvalidArgumentTypeException($class, EndpointInterface::class);
        }

        $name = $class::getName();

        $this->endpoints[$name] = $class;
        $this->endpoints[$reflection->getName()] = $class;
    }

    /**
     * @param $name
     *
     * @return EndpointInterface
     */
    public function create(string $name): EndpointInterface
    {
        $class = $this->getEndpoint($name);

        return $this->getEndpointFactory()->create($class);
    }

    /**
     * @return EndpointFactoryInterface
     */
    protected function getEndpointFactory(): EndpointFactoryInterface
    {
        if ($this->factory === null) {
            $this->setEndpointFactory(new DefaultEndpointFactory());
        }

        return $this->factory;
    }

    /**
     * @param EndpointFactoryInterface $factory
     */
    protected function setEndpointFactory(EndpointFactoryInterface $factory): void
    {
        $this->factory = $factory;
    }
}
