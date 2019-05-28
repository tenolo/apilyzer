<?php

namespace Tenolo\Apilyzer\Factory;

use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Interface EndpointFactoryInterface
 *
 * @package Tenolo\o2\TPI\Factory
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
interface EndpointFactoryInterface
{

    /**
     * @param string|EndpointInterface $name
     *
     * @return EndpointInterface
     */
    public function create($name): EndpointInterface;
}
