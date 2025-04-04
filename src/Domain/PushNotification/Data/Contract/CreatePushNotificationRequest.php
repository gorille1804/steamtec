<?php

namespace Domain\PushNotification\Data\Contract;

use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;
use Domain\User\Data\Model\User;

class CreatePushNotificationRequest
{
    public function __construct(
        public readonly User $receiver,
        public string $message,
        public MaintenanceNotificationEnum $type,
    ) {}
}