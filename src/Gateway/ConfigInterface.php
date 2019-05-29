<?php

namespace Tenolo\Apilyzer\Gateway;

use Http\Client\HttpClient;
use Http\Message\Authentication;
use JMS\Serializer\SerializerInterface;

/**
 * Interface ConfigInterface
 *
 * @package Tenolo\Apilyzer\Gateway
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
interface ConfigInterface
{

    /**
     * @inheritdoc
     */
    public function getGatewayUrl(): string;

    /**
     * @inheritdoc
     */
    public function getAuthentication(): ?Authentication;

    /**
     * @inheritdoc
     */
    public function getSerializer(): SerializerInterface;

    /**
     * @inheritdoc
     */
    public function getClient(): HttpClient;

    /**
     * @inheritdoc
     */
    public function isDebugMode(): bool;

    /**
     * @inheritdoc
     */
    public function setDebugMode(bool $debugMode): void;

    /**
     * @inheritdoc
     */
    public function getDebugVarDumperDirectory(): ?string;
}
