<?php

namespace Tenolo\Apilyzer\Endpoint;

use JMS\Serializer\SerializerInterface;
use Tenolo\Apilyzer\Call\CallInterface;

/**
 * Interface ResponseErrorInterface
 *
 * @package Tenolo\Apilyzer\Endpoint
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
interface ResponseErrorInterface
{

    /**
     * @param CallInterface       $call
     * @param SerializerInterface $serializer
     */
    public function onResponseError(CallInterface $call, SerializerInterface $serializer): void;
}
