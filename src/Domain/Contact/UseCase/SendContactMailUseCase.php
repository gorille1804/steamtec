<?php

namespace Domain\Contact\UseCase;

use Domain\Contact\Data\Contract\ContactRequest;
use Domain\Shared\Service\Email\EmailServiceInterface;

class SendContactMailUseCase implements SendContactMailUseCaseInterface
{
    public function __construct(
        private readonly EmailServiceInterface $emailService,
        private readonly string $noReplyEmail,
    ) {}

    public function __invoke(ContactRequest $request): void
    {
        $this->emailService->sendEmail(
            'email/contact/contact.html.twig',
            [
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'societe' => $request->societe,
                'email' => $request->email,
                'phone' => $request->phone,
                'type' => $request->type,
                'message' => $request->message,
            ],
            'Message de contact',
            $this->noReplyEmail,
            [$this->noReplyEmail],
        );
    }
}
