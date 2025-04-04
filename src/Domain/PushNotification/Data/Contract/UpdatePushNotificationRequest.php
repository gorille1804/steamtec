<?php

namespace Domain\PushNotification\Data\Contract;

use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;
use Domain\User\Data\Model\User;

class UpdatePushNotificationRequest
{
    public  User $receiver;
    public string $message;
    public MaintenanceNotificationEnum $type;
    public bool $status;
}