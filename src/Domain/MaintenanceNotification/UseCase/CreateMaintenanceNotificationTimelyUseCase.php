<?php

namespace Domain\MaintenanceNotification\UseCase;

use Domain\MachineLog\UseCase\SendMaintenanceMailUseCaseInteface;
use Domain\MaintenanceNotification\Data\Contract\CreateMaintenanceNotificationRequest;
use Domain\MaintenanceNotification\Data\Model\MaintenanceNotification;
use Domain\MaintenanceNotification\Factory\MaintenanceNotificationFactory;
use Domain\MaintenanceNotification\Gateway\MaintenanceNotificationRepositoryInterface;
use Domain\MaintenanceNotification\Service\EmailContentGenerator;
use Domain\MaintenanceNotification\Service\PushNotificationCreate;

class CreateMaintenanceNotificationTimelyUseCase implements CreateMaintenanceNotificationTimelyUseCaseInterface
{
    public function __construct(
        private readonly MaintenanceNotificationRepositoryInterface $repository,
        private readonly SendMaintenanceMailUseCaseInteface $sendMaintenanceMailUseCase,
        private readonly EmailContentGenerator $emailContentGenerator,
        private readonly PushNotificationCreate $pushNotificationService,
    ) {}

    public function __invoke(CreateMaintenanceNotificationRequest $request):?MaintenanceNotification
    {
        $createdAt = $request->machine->getCreatedAt();
        $now = new \DateTime();
        $hoursSinceCreation = $now->diff($createdAt)->days * 24 + $now->diff($createdAt)->h;
  
        //check if the notification already exists
        $existingNotifications = $this->repository->findByHourTimelyRange($hoursSinceCreation, $request->machine->id);
        if (empty($existingNotifications)) {
            $notification = MaintenanceNotificationFactory::make($request);
            $hoursRanges = $this->repository->findHourTimelyRange($hoursSinceCreation);
            $notification =  $this->repository->create($notification);

            //send notification to the machine
            if($notification){
                $content = $this->emailContentGenerator->generateMaintenanceEmailContent($notification, $hoursRanges);
                $this->pushNotificationService->createNotificationPush($content,$request->type->value);
                $this->sendMaintenanceMailUseCase->__invoke($request->machine, $content);
                return $notification;
            }
        }
        return null;
    }

}