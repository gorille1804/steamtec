<?php

namespace Domain\PushNotification\UseCase;

use Domain\PushNotification\Data\Model\PushNotification;
use Domain\PushNotification\Gateway\PushNotificationRepositoryInterface;

class UpdateStatusPushNotificationUseCase implements UpdateStatusPushNotificationUseCaseInterface
{
    public function __construct(
        private readonly PushNotificationRepositoryInterface $repository,
    ) {}

    public function __invoke(PushNotification $pushNotification): ?PushNotification
    {
        $pushNotification->status=!$pushNotification->status;
        return $this->repository->updateStatus($pushNotification);
    }
}
