<?php

namespace Domain\MachineLog\UseCase;

use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\Shared\Service\Email\EmailServiceInterface;
use Domain\MachineLog\UseCase\SendMaintenanceMailUseCaseInteface;

class SendMaintenanceMailUseCase implements SendMaintenanceMailUseCaseInteface
{
    public function __construct(
        private readonly EmailServiceInterface $emailService,
        private readonly string $appUrl,
        private readonly string $noReplyEmail
    ){}
    public function __invoke(ParcMachine $parcMachine): void
    {
        $user = $parcMachine->getUser();
        $machine = $parcMachine->getMachine();

       $this->emailService->sendEmail(
        'email/parcmachine/maintenance.html.twig',
        [
            'user' => $user,
            'machine' => $machine,
        ],
        'Maintenance de votre machine',
        $this->noReplyEmail,
        [ $user->getEmail()]
       );
    }
}