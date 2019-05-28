<?php

namespace Tenolo\Apilyzer\Manager;

use Tenolo\Apilyzer\Helper\EndpointFinder;

/**
 * Class EndpointManager
 *
 * @package Tenolo\o2\TPI\Manager
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class EndpointManager extends AbstractEndpointManager
{

    /** @var array */
    protected $directories;

    /** @var EndpointFinder */
    protected $finder;

    /**
     * @param $directories
     */
    public function __construct($directories)
    {
        if (!is_array($directories)) {
            $directories = [$directories];
        }

        $this->directories = $directories;
        $this->finder = new EndpointFinder();

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function load(): void
    {
        $classes = $this->finder->find($this->directories);

        foreach ($classes as $class) {
            $this->addEndpoint($class->getName());
        }
    }
}
