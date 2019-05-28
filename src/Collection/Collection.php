<?php

namespace Tenolo\Apilyzer\Collection;

use Ramsey\Collection\Exception\InvalidArgumentException;

/**
 * Class Collection
 *
 * @package Tenolo\Apilyzer\Collection
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class Collection extends \Ramsey\Collection\Collection
{
    /**
     * @inheritDoc
     */
    public function add($value): bool
    {
        $this->checkValue($value);

        $this->data[] = $value;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->checkValue($value);

        $this->data[$offset] = $value;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value): void
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @param $value
     */
    protected function checkValue($value): void
    {
        if ($this->checkType($this->getType(), $value) === false) {
            throw new InvalidArgumentException(
                'Value must be of type '.$this->getType().'; value is '
                .$this->toolValueToString($value)
            );
        }
    }
}
