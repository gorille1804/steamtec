<?php

namespace Infrastructure\Symfony\Services\Email;
use Domain\Shared\Service\Email\EmailServiceInterface;
class EmailService implements EmailServiceInterface
{
    private EmailSenderInterface $emailSender;

    public function __construct(EmailSenderInterface $emailSender) {
        $this->emailSender = $emailSender;
    }

    public function sendEmail(
        string $templatePath,
        array $context,
        string $subject,
        string $fromEmail,
        array $toEmails,
        array $customHeaders = [],
        array $attachments = []
    ): void {
        $this->emailSender->sendEmail(
            $templatePath,
            $context,
            $subject,
            $fromEmail,
            $toEmails,
            $customHeaders,
            $attachments
        );
    }
}
