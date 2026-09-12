<?php

namespace App\Traits;

trait Makeable
{
    /**
     * Create a new instance of the class with the given parameters.
     *
     * This method uses the variadic parameter syntax to accept
     * any number of arguments, which are then passed to the class constructor.
     *
     * @param mixed ...$parameters
     * @return static
     */
    public static function make(...$parameters): static
    {
        return new static(...$parameters);
    }
}
