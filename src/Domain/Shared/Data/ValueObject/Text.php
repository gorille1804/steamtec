<?php

namespace Domain\Shared\Data\ValueObject;

/**
 * Class Text.
 */
abstract class Text implements \Stringable
{
    abstract public function getValue(): string;

    public function __toString(): string
    {
        return $this->getValue();
    }
}
