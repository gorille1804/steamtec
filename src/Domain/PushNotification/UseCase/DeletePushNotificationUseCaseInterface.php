<?php

namespace Domain\PushNotification\UseCase;

use Domain\PushNotification\Data\Model\PushNotification;

interface DeletePushNotificationUseCaseInterface
{
    public function __invoke(PushNotification $pushNotification): void;
}