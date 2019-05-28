<?php

namespace Tenolo\Apilyzer\Call;

use Http\Client\Common\Plugin;
use Http\Client\Exception;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ClientCallPlugin
 *
 * @package Tenolo\o2\TPI\Plugin
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class CallPlugin implements Plugin
{

    /** @var CallInterface */
    protected $call;

    /**
     * @param CallInterface $call
     */
    public function __construct(CallInterface $call)
    {
        $this->call = $call;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $this->call->setRequest($request);

        return $next($request)->then(function (ResponseInterface $response) {
            $this->call->setResponse($response);

            return $response;
        }, function (Exception $exception) {
            if ($exception instanceof Exception\HttpException) {
                $this->call->setResponse($exception->getResponse());
            }

            throw $exception;
        });
    }
}
