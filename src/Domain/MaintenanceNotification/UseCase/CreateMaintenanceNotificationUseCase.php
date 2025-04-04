<?php

namespace Domain\MaintenanceNotification\UseCase;

use Domain\MachineLog\UseCase\SendMaintenanceMailUseCaseInteface;
use Domain\MaintenanceNotification\Data\Constant\MaintenanceNotification as ConstantMaintenanceNotification;
use Domain\MaintenanceNotification\Data\Contract\CreateMaintenanceNotificationRequest;
use Domain\MaintenanceNotification\Factory\MaintenanceNotificationFactory;
use Domain\MaintenanceNotification\Gateway\MaintenanceNotificationRepositoryInterface;
use Domain\MaintenanceNotification\Service\EmailContentGenerator;
use Domain\MaintenanceNotification\Service\PushNotificationCreate;

class CreateMaintenanceNotificationUseCase implements CreateMaintenanceNotificationUseCaseInterface
{
    public function __construct(
        private readonly MaintenanceNotificationRepositoryInterface $repository,
        private readonly SendMaintenanceMailUseCaseInteface $sendMaintenanceMailUseCase,
        private readonly EmailContentGenerator $emailContentGenerator,  
        private readonly PushNotificationCreate $pushNotificationService,
    ) {}

    public function __invoke(CreateMaintenanceNotificationRequest $request): void
    {
        //check if the notification already exists
        $existingNotification = $this->repository->findByHourRange($request->hours, $request->machine->id);
        if (!$existingNotification && 
            $request->hours >= ConstantMaintenanceNotification::MINIMAL_HOURS && 
            $request->hours <= ConstantMaintenanceNotification::MAXIMAL_HOURS
        ) {
            $notification = MaintenanceNotificationFactory::make($request);
            $notification =  $this->repository->create($notification);
            //send notification to the machine
            $content = $this->emailContentGenerator->generateMaintenanceEmailContent($notification);
            $this->pushNotificationService->createNotificationPush($content,$request->type->value);
            $this->sendMaintenanceMailUseCase->__invoke($request->machine, $content);
        }
    }

}