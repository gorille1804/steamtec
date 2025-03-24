<?php

namespace Domain\MaintenanceNotification\UseCase;

use Domain\MaintenanceNotification\Data\Contract\CreateMaintenanceNotificationRequest;
use Domain\MaintenanceNotification\Data\Model\MaintenanceNotification;

interface CreateMaintenanceNotificationUseCaseInterface
{
    public function __invoke(CreateMaintenanceNotificationRequest $request): void;
}