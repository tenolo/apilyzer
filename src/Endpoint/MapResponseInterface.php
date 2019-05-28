<?php

namespace Tenolo\Apilyzer\Endpoint;

use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface MapResponseInterface
 *
 * @package Tenolo\Apilyzer\Endpoint
 * @author  Nikita Loges
 * @company tenolo GbR
 */
interface MapResponseInterface
{

    /**
     * @param ResponseInterface   $response
     * @param SerializerInterface $serializer
     *
     * @return ResponseInterface
     */
    public function mapResponse(ResponseInterface $response, SerializerInterface $serializer): ResponseInterface;
}
