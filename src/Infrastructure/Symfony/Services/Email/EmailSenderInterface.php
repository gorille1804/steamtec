<?php

namespace Infrastructure\Symfony\Services\Email;

interface EmailSenderInterface
{
    public function sendEmail(
        string $templatePath,
        array $context,
        string $subject,
        string $fromEmail,
        array $toEmails,
        array $customHeaders = [],
        array $attachments = []
    ): void;
}
