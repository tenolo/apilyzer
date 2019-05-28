<?php

namespace Tenolo\Apilyzer\Factory;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

/**
 * Class DefaultSerializerFactory
 *
 * @package Tenolo\Apilyzer\Factory
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class DefaultSerializerFactory implements SerializerFactoryInterface
{

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * @return SerializerInterface
     */
    public function create(): SerializerInterface
    {
        if ($this->serializer === null) {
            $this->serializer = $this->init();
        }

        return $this->serializer;
    }

    /**
     * @return SerializerInterface
     */
    protected function init(): SerializerInterface
    {
        $serializerBuilder = SerializerBuilder::create();

        return $serializerBuilder->build();
    }
}
