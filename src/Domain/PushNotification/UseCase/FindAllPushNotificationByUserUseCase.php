<?php
Namespace Domain\PushNotification\UseCase;

use Domain\PushNotification\Gateway\PushNotificationRepositoryInterface;
use Domain\User\Data\Model\User;

class FindAllPushNotificationByUserUseCase implements FindAllPushNotificationByUserUseCaseInterface
{
    public function __construct(
        private readonly PushNotificationRepositoryInterface $repository,
    )
    {}
    
    public function __invoke(User $user): array
    {
        $PushNotifications= $this->repository->getAllByUser($user);
        return $PushNotifications;
    }
}