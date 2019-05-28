<?php

namespace Tenolo\Apilyzer\Event;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\Event;
use Tenolo\Apilyzer\Call\CallInterface;

/**
 * Class ResponseErrorEvent
 *
 * @package Tenolo\Apilyzer\Event
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class ResponseErrorEvent extends Event
{

    /** @var CallInterface */
    protected $call;

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * @param CallInterface       $call
     * @param SerializerInterface $serializer
     */
    public function __construct(CallInterface $call, SerializerInterface $serializer)
    {
        $this->call = $call;
        $this->serializer = $serializer;
    }

    /**
     * @return CallInterface
     */
    public function getCall(): CallInterface
    {
        return $this->call;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }
}
