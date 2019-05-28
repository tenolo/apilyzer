<?php

namespace Tenolo\Apilyzer\Factory;

use JMS\Serializer\SerializerInterface;

/**
 * Interface SerializerFactoryInterface
 *
 * @package Tenolo\o2\TPI\Factory
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
interface SerializerFactoryInterface
{

    /**
     * @return SerializerInterface
     */
    public function create(): SerializerInterface;
}
