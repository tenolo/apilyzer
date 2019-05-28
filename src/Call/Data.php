<?php

namespace Tenolo\Apilyzer\Call;

/**
 * Class Data
 *
 * @package Tenolo\Apilyzer\Gateway
 * @author  Nikita Loges
 * @company tenolo GmbH & Co. KG
 */
class Data
{

    /** @var mixed */
    protected $original;

    /** @var mixed */
    protected $normalized;

    /**
     * @param null $original
     * @param null $normalized
     */
    public function __construct($original = null, $normalized = null)
    {
        $this->original = $original;
        $this->normalized = $normalized;
    }

    /**
     * @return mixed
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * @param mixed $original
     */
    public function setOriginal($original): void
    {
        $this->original = $original;
    }

    public function hasOriginal(): bool
    {
        return $this->original !== null;
    }

    /**
     * @return mixed
     */
    public function getNormalized()
    {
        return $this->normalized;
    }

    /**
     * @param mixed $normalized
     */
    public function setNormalized($normalized): void
    {
        $this->normalized = $normalized;
    }

    /**
     * @return bool
     */
    public function hasNormalized(): bool
    {
        return $this->normalized !== null;
    }
}
