<?php

namespace Domain\PushNotification\Data\ValueObject;

use Domain\Shared\Data\ValueObject\Text;
use Ramsey\Uuid\Uuid;

class PushNotificationId extends Text
{
    private readonly string $uuid;


    /**
     * PushNotificationId constructor.
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function make(): PushNotificationId
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
