<?php

namespace Domain\MaintenanceNotification\Service;

use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;
use Domain\PushNotification\Data\Contract\CreatePushNotificationRequest;
use Domain\PushNotification\UseCase\CreatePushNotificationUseCaseInterface;
use Domain\Shared\TokenStorageInterface\CurrentUserProviderInterface;

class PushNotificationCreate
{
    public function __construct(
        private readonly CreatePushNotificationUseCaseInterface $createUseCase,
        private readonly CurrentUserProviderInterface $currentUserProvider
    ){}
    
    public function createNotificationPush(string $message, string $type): void
    {
        $receiver = $this->currentUserProvider->getCurrentUser();
        
        $notificationRequest = new CreatePushNotificationRequest(
            $receiver,
            $message,
            MaintenanceNotificationEnum::from($type),
        );

        $this->createUseCase->__invoke($notificationRequest);
    }
}