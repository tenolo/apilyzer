![tenolo](https://tenolo.de/themes/486/img/tenolo_werbeagentur_bochum.png)

[![Latest Stable Version](https://img.shields.io/packagist/php-v/tenolo/apilyzer.svg)](https://packagist.org/packages/tenolo/apilyzer)
[![Latest Stable Version](https://poser.pugx.org/tenolo/apilyzer/version)](https://packagist.org/packages/tenolo/apilyzer)
[![Total Downloads](https://poser.pugx.org/tenolo/apilyzer/downloads)](https://packagist.org/packages/tenolo/apilyzer)
[![Monthly Downloads](https://poser.pugx.org/tenolo/apilyzer/d/monthly)](https://packagist.org/packages/tenolo/apilyzer)
[![Latest Unstable Version](https://poser.pugx.org/tenolo/apilyzer/v/unstable)](//packagist.org/packages/tenolo/apilyzer)
[![License](https://poser.pugx.org/tenolo/apilyzer/license)](https://packagist.org/packages/tenolo/apilyzer)

# Apilyzer

A library for easy creation of REST API clients.

## Install instructions

### Composer 

First you need to add `tenolo/apilyzer` to `composer.json`:

Do it manually 

``` json
{
   "require": {
        "tenolo/apilyzer": "~1.0"
    }
}
```

or just execute `composer require tenolo/apilyzer`.

Please note that `dev-master` latest development version. 
Of course you can also use an explicit version number, e.g., `1.0.*` or `^1.0`.

### HTTP Client and Factory

As a second step you have to add HTTP-Client and a HTTP-Factory to your project in production or development environment.

You need one or more libraries that implements following packages:

* psr/http-message
* psr/http-client
* psr/http-factory-implementation
* php-http/client-implementation

This library intentionally does not provide packages so that each API client can implement its own.

We recommend the use of `nyholm/psr7` and `php-http/guzzle6-adapter`.

For production use: `composer require nyholm/psr7 php-http/guzzle6-adapter` <br>
For development use: `composer require --dev nyholm/psr7 php-http/guzzle6-adapter`

## Usage

### First Steps

Create your own `Gateway` and `Config` class.

```php
<?php

namespace App\Api\Gateway;

use Tenolo\Apilyzer\Gateway\Config as BaseConfig;

/**
 * Class Config
 */
class Config extends BaseConfig
{

    /**
     * @inheritDoc
     */
    public function getGatewayUrl(): string
    {
        return 'https://BASE.URL.TO.API.com/';
    }
}

```

```php
<?php

namespace App\Api\Gateway;

use Tenolo\Apilyzer\Gateway\Gateway as BaseGateway;
use Tenolo\Apilyzer\Manager\EndpointManager;
use Tenolo\Apilyzer\Manager\EndpointManagerInterface;

/**
 * Class Gateway
 */
class Gateway extends BaseGateway
{

    /** @var EndpointManagerInterface */
    protected $endpointManager;

    /**
     * @inheritDoc
     */
    protected function getEndpointManager(): EndpointManagerInterface
    {
        if ($this->endpointManager === null) {
            $this->endpointManager = $this->createEndpointManager();
        }

        return $this->endpointManager;
    }
    
    /**
    * @return EndpointManagerInterface
    */
    protected function createEndpointManager(): EndpointManagerInterface 
    {
        return new EndpointManager(__DIR__.'/../Endpoint');
    }
}

```
