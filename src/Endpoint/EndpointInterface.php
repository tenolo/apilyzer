<?php

namespace Tenolo\Apilyzer\Endpoint;

use Psr\Http\Message\ResponseInterface;
use Ramsey\Collection\CollectionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Route;

/**
 * Interface EndpointInterface
 *
 * @package Tenolo\Apilyzer\Endpoint
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
interface EndpointInterface
{

    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @return mixed
     */
    public static function getCallUri(): string;

    /**
     * @return string
     */
    public static function getRouteName(): string;

    /**
     * @inheritDoc
     */
    public static function configureRoute(Route $route);

    /**
     * @param CollectionInterface $collection
     * @param array               $options
     */
    public function setRouteParameters(CollectionInterface $collection, array $options): void;

    /**
     * @param array $options
     *
     * @return string
     */
    public function getMethod(array $options): string;

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void;

    /**
     * @param CollectionInterface $collection
     * @param array               $options
     */
    public function getPlugins(CollectionInterface $collection, array $options): void;

    /**
     * @param CollectionInterface $collection
     * @param array               $options
     */
    public function getHttpHeaders(CollectionInterface $collection, array $options): void;

    /**
     * @param array $options
     *
     * @return bool
     */
    public function supportsSerialization(array $options): bool;

    /**
     * @param array $options
     *
     * @return bool
     */
    public function serializationIsRequired(array $options): bool;

    /**
     * @param CollectionInterface $collection
     */
    public function getSerializationTypes(CollectionInterface $collection): void;

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function supportsDeserialization(ResponseInterface $response): bool;

    /**
     * @param array $options
     *
     * @return string
     */
    public function getSerializationFormat(array $options): string;

    /**
     * @param ResponseInterface $response
     *
     * @return null|string
     */
    public function getDeserializationType(ResponseInterface $response): ?string;

    /**
     * @param ResponseInterface $response
     *
     * @return string
     */
    public function getDeserializationFormat(ResponseInterface $response): string;

    /**
     * @param array $options
     *
     * @return bool
     */
    public function isAuthenticationRequired(array $options): bool;
}
