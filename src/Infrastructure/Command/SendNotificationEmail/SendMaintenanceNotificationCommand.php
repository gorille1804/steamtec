<?php 
namespace Infrastructure\Command\SendNotificationEmail;

use Domain\MachineLog\UseCase\MaintenanceEmailContentUseCaseInterface;
use Domain\MachineLog\UseCase\SendMaintenanceMailUseCaseInteface;
use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\MaintenanceNotification\Data\Enum\MaintenanceNotificationEnum;
use Domain\MaintenanceNotification\Factory\CreateMaitenanceNotificationRequestFactory;
use Domain\MaintenanceNotification\Service\MaintenanceCheckerService;
use Domain\MaintenanceNotification\UseCase\CreateMaintenanceNotificationTimelyUseCaseInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'send-notification:email',
    description: 'Send maintenance notification if a machine reaches its maintenance threshold'
)]
class SendMaintenanceNotificationCommand extends Command
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $parcMachineRepository,
        private readonly SendMaintenanceMailUseCaseInteface $sendMaintenanceMailUseCase,
        private readonly MaintenanceCheckerService $maintenanceChecker,
        private readonly MaintenanceEmailContentUseCaseInterface $maintenanceEmailContent,
        private readonly CreateMaintenanceNotificationTimelyUseCaseInterface $maitenanceNotificationTimelyUseCase,
 
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Checking machines for maintenance threshold...</info>');

        $machinesToCheck = $this->parcMachineRepository->getAll();

        if (empty($machinesToCheck)) {
            $output->writeln('<comment>No machines require maintenance notifications.</comment>');
            return Command::SUCCESS;
        }

        foreach ($machinesToCheck as $parcMachine) {
            if ($this->maintenanceChecker->maintenanceChecker($parcMachine)) {
                $maitenanceNotificationRequest = CreateMaitenanceNotificationRequestFactory::make(
                    MaintenanceNotificationEnum::TIMELY_MAINTENANCE, 
                    $parcMachine,
                    $parcMachine->getTempUsage()
                );
                $notification = $this->maitenanceNotificationTimelyUseCase->__invoke($maitenanceNotificationRequest); 
                if ($notification !== null) {
                    $machineName = $parcMachine->getMachine()->getNom();
                    $user=$parcMachine->getUser()->getEmail();
                    $output->writeln(sprintf('<info>- Notification sent for machine: %s of user: %s</info>', $machineName, $user));    
                }
             }
        }

        $output->writeln('<info>Maintenance notification process completed.</info>');
        return Command::SUCCESS;
    }
}