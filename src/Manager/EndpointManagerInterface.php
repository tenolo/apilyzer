<?php

namespace Tenolo\Apilyzer\Manager;

use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Interface EndpointManagerInterface
 *
 * @package Tenolo\o2\TPI\Manager
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
interface EndpointManagerInterface
{

    /**
     * @return string[]|EndpointInterface[]
     */
    public function getEndpointsAsArray(): array;

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasEndpoint($name): bool;

    /**
     * @param $name
     *
     * @return string|EndpointInterface
     */
    public function getEndpoint($name);

    /**
     * @param string|EndpointInterface $class
     */
    public function addEndpoint($class);

    /**
     * @param string $name
     *
     * @return EndpointInterface
     */
    public function create(string $name): EndpointInterface;
}
