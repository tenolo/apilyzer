<?php

namespace Tenolo\Apilyzer\Gateway;

use Http\Message\Authentication;
use Tenolo\Apilyzer\Call\CallInterface;

/**
 * Interface GatewayInterface
 *
 * @package Tenolo\Apilyzer\Gateway
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
interface GatewayInterface
{

    /**
     * @param string              $name
     * @param mixed|null          $body
     * @param array               $options
     * @param array               $headers
     * @param array               $plugins
     * @param Authentication|null $authentication
     *
     * @return CallInterface
     */
    public function call(
        string $name,
        $body = null,
        array $options = [],
        array $headers = [],
        array $plugins = [],
        Authentication $authentication = null
    ): CallInterface;
}
