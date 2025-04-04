<?php

namespace Domain\PushNotification\UseCase;

use Domain\PushNotification\Data\Contract\CreatePushNotificationRequest;
use Domain\PushNotification\Data\Model\PushNotification;

interface CreatePushNotificationUseCaseInterface
{
    public function __invoke(CreatePushNotificationRequest $request): ?PushNotification;
}