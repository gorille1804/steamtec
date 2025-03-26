<?php

namespace Domain\MachineLog\UseCase;

use Domain\ParcMachine\Data\Model\ParcMachine;
use Domain\Shared\Service\Email\EmailServiceInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Domain\MachineLog\UseCase\SendMaintenanceMailUseCaseInteface;
use Domain\MaintenanceNotification\Data\Constant\MaintenanceNotification;

class SendMaintenanceMailUseCase implements SendMaintenanceMailUseCaseInteface
{
    public function __construct(
        private readonly EmailServiceInterface $emailService,
        private readonly string $appUrl,
        private readonly TranslatorInterface $translator,
        private readonly string $noReplyEmail,
    ){}
    public function __invoke(ParcMachine $parcMachine, ?string $content): void
    {
        $user = $parcMachine->getUser();
        $machine = $parcMachine->getMachine();
        $pdfPath = MaintenanceNotification::URL_FILE;
       $this->emailService->sendEmail(
        'email/parcmachine/maintenance.html.twig',
        [
            'user' => $user,
            'machine' => $machine,
            'content' => $content,
        ],
        $this->translator->trans('chantiers.maintenance_notification_mail.subject'),
        $this->noReplyEmail,
        [ $user->getEmail()],
        [],
        [
            [
                'path' => $pdfPath,
                'name' => 'Entretien_Machine.pdf',
                'contentType' => 'application/pdf'
            ]
        ]        
       );
    }
}