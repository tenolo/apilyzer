<?php

namespace Tenolo\Apilyzer\Call;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Interface CallInterface
 *
 * @package Tenolo\Apilyzer\Gateway
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
interface CallInterface
{

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;

    /**
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request);

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface;

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response);

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @return string|null
     */
    public function getReasonPhrase(): ?string;

    /**
     * @return bool
     */
    public function isSubmitted(): bool;

    /**
     * @return Data
     */
    public function getSubmittedData(): Data;

    /**
     * @return Data
     */
    public function getReceivedData(): Data;

    /**
     * @return mixed
     */
    public function getSubmittedDataNormalized();

    /**
     * @return mixed
     */
    public function getReceivedDataNormalized();

    /**
     * @return mixed
     */
    public function getOriginalSubmittedBody();

    /**
     * @return mixed
     */
    public function getOriginalReceivedBody();

    /**
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * @return bool
     */
    public function isForwarding(): bool;

    /**
     * @return bool
     */
    public function isError(): bool;

    /**
     * @return bool
     */
    public function isClientError(): bool;

    /**
     * @return bool
     */
    public function isServerError(): bool;

    /**
     * @return int
     */
    public function getRequestTime(): int;

    /**
     * @param int $requestTime
     */
    public function setRequestTime(int $requestTime): void;

    /**
     * @return int
     */
    public function getMemory(): int;

    /**
     * @param int $memory
     */
    public function setMemory(int $memory): void;

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface;
}
