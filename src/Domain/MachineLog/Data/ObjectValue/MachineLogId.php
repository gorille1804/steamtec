<?php

namespace Domain\MachineLog\Data\ObjectValue;

use Domain\Shared\Data\ValueObject\Text;
use Ramsey\Uuid\Uuid;

class MachineLogId extends Text implements \JsonSerializable
{
    private readonly string $uuid;

    /**
     * MachineLogId constructor.
     */

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function make(): MachineLogId
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