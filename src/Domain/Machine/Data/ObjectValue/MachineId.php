<?php

namespace Domain\Machine\Data\ObjectValue;

use Domain\Shared\Data\ValueObject\Text;
use Ramsey\Uuid\Uuid;

class MachineId extends Text implements \JsonSerializable
{
    private readonly string $uuid;


    /**
     * MachineId constructor.
     */

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function make(): MachineId
    {
        return new self(Uuid::uuid4());
    }

    public function getValue(): string
    {
        return $this->uuid;
    }

    public function jsonSerialize(): string
    {
        return $this->uuid;
    }
}