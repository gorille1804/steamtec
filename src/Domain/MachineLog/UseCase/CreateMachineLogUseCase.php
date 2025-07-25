<?php

namespace Domain\MachineLog\UseCase;

use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\MachineLog\Factory\MachineLogFactory;
use Domain\MachineLog\Gateway\MachineLogRepositoryInterface;
use Domain\MachineLog\UseCase\CreateMachineLogUseCaseInterface;
use Domain\MachineLog\Data\Contract\CreateMachineLogRequest;
use Domain\ParcMachine\Factory\ParcMachineFactory;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\MachineLog\UseCase\SendMaintenanceMailUseCaseInteface;
use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;
use Domain\MaintenanceNotification\Factory\CreateMaitenanceNotificationRequestFactory;
use Domain\MaintenanceNotification\UseCase\CreateMaintenanceNotificationUseCaseInterface;

class CreateMachineLogUseCase implements CreateMachineLogUseCaseInterface
{
    public function __construct(
        private readonly MachineLogRepositoryInterface $repository,
        private readonly ParcMachineRepositoryInterface $parcMachineRepository,
        private readonly SendMaintenanceMailUseCaseInteface $sendMaintenanceMailUseCase,
        private readonly CreateMaintenanceNotificationUseCaseInterface $maitenanceNotificationUseCase,
    ) {
    }

    public function __invoke(CreateMachineLogRequest $request, Chantier $chantier): void
    {
        $machileLogs = MachineLogFactory::make($request, $chantier);
        
        if (count($machileLogs) > 0) {
            foreach ($machileLogs as $log) {
                $log = $this->repository->create($log);
                
                // Update ParcMachine aggregate hours
                $oldCurrentHourUse = $log->parcMachine->currentHourUse;
                $parcMachine = ParcMachineFactory::updateDuration($log->parcMachine, $log->duration);
                $this->parcMachineRepository->update($parcMachine);

                if ($oldCurrentHourUse + $log->duration >= $parcMachine->machine->seuilMaintenance) {
                    $this->sendMaintenanceMailUseCase->__invoke($parcMachine, null);
                }

                $maitenanceNotificationRequest = CreateMaitenanceNotificationRequestFactory::make(
                    MaintenanceNotificationEnum::REGULAR_MAINTENANCE, 
                    $log->parcMachine,
                    $parcMachine->tempUsage
                );
                $this->maitenanceNotificationUseCase->__invoke($maitenanceNotificationRequest);
            }
        }
    }
}