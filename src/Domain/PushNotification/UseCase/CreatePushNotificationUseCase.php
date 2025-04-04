<?php

namespace Domain\PushNotification\UseCase;

use Domain\PushNotification\Data\Contract\CreatePushNotificationRequest;
use Domain\PushNotification\Data\Model\PushNotification;
use Domain\PushNotification\Factory\PushNotificationFactory;
use Domain\PushNotification\Gateway\PushNotificationRepositoryInterface;

class CreatePushNotificationUseCase implements CreatePushNotificationUseCaseInterface
{
    public function __construct(
        private readonly PushNotificationRepositoryInterface $repository,
    ) {}

    public function __invoke(CreatePushNotificationRequest $request): ?PushNotification
    {
        $notification = PushNotificationFactory::make($request);
        return $this->repository->save($notification);
    }
}
