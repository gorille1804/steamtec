<?php

namespace Domain\PushNotification\Gateway;

use Domain\PushNotification\Data\Model\PushNotification;
use Domain\User\Data\Model\User;

interface PushNotificationRepositoryInterface
{
    public function getAllByUser(User $user): array;
    public function updateStatus(PushNotification $PushNotification): ?PushNotification;
    public function delete(PushNotification $PushNotification): void;
    public function save(PushNotification $PushNotification): PushNotification;
}