<?php

namespace Tenolo\Apilyzer\Collection;

use Ramsey\Collection\CollectionInterface as RamCollectionInterface;

/**
 * Interface CollectionInterface
 *
 * @package Tenolo\Apilyzer\Collection
 * @author  Nikita Loges
 * @company tenolo GbR
 */
interface CollectionInterface extends RamCollectionInterface
{

    /**
     * @param string $name
     * @param        $value
     */
    public function set(string $name, $value): void;
}
