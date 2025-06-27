<?php

namespace Domain\PushNotification\UseCase;

use Domain\User\Data\Model\User;

interface FindAllPushNotificationByUserUseCaseInterface
{
    public function __invoke(User $user):array;
}