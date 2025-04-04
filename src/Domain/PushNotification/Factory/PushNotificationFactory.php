<?php

namespace Domain\PushNotification\Factory;

use Domain\PushNotification\Data\Contract\CreatePushNotificationRequest;
use Domain\PushNotification\Data\Model\PushNotification;
use Domain\PushNotification\Data\ValueObject\PushNotificationId;

class PushNotificationFactory
{
    /**
     * Creates a new PushNotification instance from the creation request.
     *
     * @param CreatePushNotificationRequest $request
     * @return PushNotification
     */
    public static function make(CreatePushNotificationRequest $request): PushNotification
    {
        return new PushNotification(
            PushNotificationId::make(),
            $request->receiver,
            $request->message,
            $request->type,
            false,
            new \DateTimeImmutable(),
        );
    }

    public static function update(PushNotification $PushNotification): PushNotification
    {
        $PushNotification->status = true;
        return $PushNotification;
    }
}