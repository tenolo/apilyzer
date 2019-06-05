<?php

namespace Tenolo\Apilyzer\Endpoint;

use Psr\Http\Message\ResponseInterface;
use Tenolo\Apilyzer\Collection\CollectionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Route;

/**
 * Class AbstractEndpoint
 *
 * @package Tenolo\Apilyzer\Endpoint
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
abstract class AbstractEndpoint implements EndpointInterface
{
    /**
     * @inheritDoc
     */
    public static function getRouteName(): string
    {
        return static::getName();
    }

    /**
     * @inheritDoc
     */
    public static function getName(): string
    {
        $className = static::class;

        $plodes = explode('Endpoint\\', $className);

        unset($plodes[0]);

        $className = implode('', $plodes);
        $className = lcfirst(str_replace('\\', '', ucwords($className, '_')));

        return $className;
    }

    /**
     * @inheritDoc
     */
    public static function configureRoute(Route $route): void
    {
    }

    /**
     * @inheritDoc
     */
    public function setRouteParameters(CollectionInterface $collection, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getMethod(array $options): string
    {
        return 'GET';
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getPlugins(CollectionInterface $collection, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getHttpHeaders(CollectionInterface $collection, array $options): void
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsSerialization(array $options): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function serializationIsRequired(array $options): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getSerializationTypes(CollectionInterface $collection): void
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsDeserialization(ResponseInterface $response): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getSerializationFormat(array $options): string
    {
        return 'json';
    }

    /**
     * @inheritDoc
     */
    public function getDeserializationType(ResponseInterface $response): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getDeserializationFormat(ResponseInterface $response): string
    {
        return 'json';
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticationRequired(array $options): bool
    {
        return true;
    }
}
