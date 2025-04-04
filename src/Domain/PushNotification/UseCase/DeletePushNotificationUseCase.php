<?php
namespace Domain\PushNotification\UseCase;

use Domain\PushNotification\Data\Model\PushNotification;
use Domain\PushNotification\Gateway\PushNotificationRepositoryInterface;


class DeletePushNotificationUseCase implements DeletePushNotificationUseCaseInterface
{
    public function __construct(
        private readonly PushNotificationRepositoryInterface $repository,
    ){}
    public function __invoke(PushNotification $pushNotification): void
    {    
        $this->repository->delete($pushNotification);
    }
}