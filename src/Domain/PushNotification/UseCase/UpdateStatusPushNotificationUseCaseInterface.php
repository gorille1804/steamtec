<?php

namespace Domain\PushNotification\UseCase;

use Domain\PushNotification\Data\Model\PushNotification;

interface UpdateStatusPushNotificationUseCaseInterface
{
    public function __invoke(PushNotification $pushNotification): ?PushNotification;
}