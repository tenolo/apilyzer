<?php

namespace Tenolo\Apilyzer\Call;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tenolo\Apilyzer\Endpoint\EndpointInterface;

/**
 * Class Call
 *
 * @package Tenolo\Apilyzer\Gateway
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class Call implements CallInterface
{

    /** @var RequestInterface */
    protected $request;

    /** @var ResponseInterface */
    protected $response;

    /** @var Data|null */
    protected $submittedData;

    /** @var Data|null */
    protected $receivedData;

    /** @var integer */
    protected $requestTime;

    /** @var integer */
    protected $memory;

    /** @var */
    protected $errors;

    /** @var */
    protected $endpoint;

    /**
     * @param EndpointInterface $endpoint
     * @param Data|null         $submittedData
     * @param Data|null         $receivedData
     * @param int|null          $requestTime
     * @param int|null          $memory
     * @param array|null        $errors
     */
    public function __construct(
        EndpointInterface $endpoint,
        ?Data $submittedData = null,
        ?Data $receivedData = null,
        int $requestTime = null,
        int $memory = null,
        array $errors = null
    ) {
        $this->endpoint = $endpoint;
        $this->submittedData = $submittedData ?? new Data();
        $this->receivedData = $receivedData ?? new Data();
        $this->requestTime = $requestTime;
        $this->memory = $memory;
        $this->errors = $errors;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @inheritDoc
     */
    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return $this->getResponse()->getStatusCode();
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): ?string
    {
        return $this->getResponse()->getReasonPhrase();
    }

    /**
     * @inheritDoc
     */
    public function getSubmittedData(): Data
    {
        return $this->submittedData;
    }

    /**
     * @inheritDoc
     */
    public function getReceivedData(): Data
    {
        return $this->receivedData;
    }

    /**
     * @return mixed
     */
    public function getSubmittedDataNormalized()
    {
        if (null === $this->getSubmittedData()) {
            return null;
        }

        return $this->getSubmittedData()->getNormalized();
    }

    /**
     * @return mixed
     */
    public function getReceivedDataNormalized()
    {
        if (null === $this->getReceivedData()) {
            return null;
        }

        return $this->getReceivedData()->getNormalized();
    }

    /**
     * @return mixed
     */
    public function getOriginalSubmittedBody()
    {
        if (null === $this->getSubmittedData()) {
            return null;
        }

        return $this->getSubmittedData()->getOriginal();
    }

    /**
     * @return mixed
     */
    public function getOriginalReceivedBody()
    {
        if (null === $this->getReceivedData()) {
            return null;
        }

        return $this->getReceivedData()->getOriginal();
    }

    /**
     * @inheritdoc
     */
    public function isSuccessful(): bool
    {
        $statusCode = $this->getResponse()->getStatusCode();

        return ($statusCode >= 200 && $statusCode < 300);
    }

    /**
     * @inheritdoc
     */
    public function isForwarding(): bool
    {
        $statusCode = $this->getResponse()->getStatusCode();

        return ($statusCode >= 300 && $statusCode < 400);
    }

    /**
     * @inheritdoc
     */
    public function isError(): bool
    {
        if ($this->isClientError()) {
            return true;
        }

        if ($this->isServerError()) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function isClientError(): bool
    {
        $statusCode = $this->getResponse()->getStatusCode();

        return ($statusCode >= 400 && $statusCode < 500);
    }

    /**
     * @inheritdoc
     */
    public function isServerError(): bool
    {
        $statusCode = $this->getResponse()->getStatusCode();

        return ($statusCode >= 500 && $statusCode < 600);
    }

    /**
     * @return int
     */
    public function getRequestTime(): int
    {
        return $this->requestTime;
    }

    /**
     * @param int $requestTime
     */
    public function setRequestTime(int $requestTime): void
    {
        $this->requestTime = $requestTime;
    }

    /**
     * @return int
     */
    public function getMemory(): int
    {
        return $this->memory;
    }

    /**
     * @param int $memory
     */
    public function setMemory(int $memory): void
    {
        $this->memory = $memory;
    }

    /**
     * @return EndpointInterface
     */
    public function getEndpoint(): EndpointInterface
    {
        return $this->endpoint;
    }
}
