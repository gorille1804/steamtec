<?php

namespace Domain\MaintenanceNotification\Data\ValueObject;

use Domain\Shared\Data\ValueObject\Text;
use Ramsey\Uuid\Uuid;

class MaintenanceNotificationId extends Text
{
    private readonly string $uuid;


    /**
     * MaintenanceNotificationId constructor.
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function make(): MaintenanceNotificationId
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
