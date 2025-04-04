<?php

namespace Domain\PushNotification\Data\Model;

use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;
use Domain\PushNotification\Data\ValueObject\PushNotificationId;
use Domain\User\Data\Model\User;

class PushNotification
{
    public function __construct(
        public readonly PushNotificationId $id,
        public  User $receiver,
        public string $message,
        public MaintenanceNotificationEnum $type,
        public bool $status,
        public \DateTimeInterface $createdAt
    ) {}
}