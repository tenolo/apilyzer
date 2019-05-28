<?php

namespace Tenolo\Apilyzer\Factory;

use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class DefaultEndpointFactory
 *
 * @package Tenolo\Apilyzer\Factory
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class DefaultEndpointFactory implements EndpointFactoryInterface
{

    /**
     * @inheritdoc
     */
    public function create($name): EndpointInterface
    {
        if (is_object($name)) {
            return $name;
        }

        return new $name();
    }
}
