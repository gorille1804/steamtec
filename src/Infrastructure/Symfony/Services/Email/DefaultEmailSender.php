<?php
namespace Infrastructure\Symfony\Services\Email;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class DefaultEmailSender implements EmailSenderInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig
    ) {}

    public function sendEmail(
        string $templatePath,
        array $context,
        string $subject,
        string $fromEmail,
        array $toEmails,
        array $customHeaders = [],
        array $attachments = []
    ): void {
        $htmlContent = $this->twig->render($templatePath, $context);

        $email = (new Email())
            ->from(new Address($fromEmail))
            ->subject($subject)
            ->html($htmlContent);
            
        foreach ($toEmails as $toEmail) {
            $email->addTo(new Address($toEmail));
        }

        foreach ($customHeaders as $key => $value) {
            $email->getHeaders()->addTextHeader($key, $value);
        }

        foreach ($attachments as $attachment) {
            if (isset($attachment['path']) && file_exists($attachment['path'])) {
                $email->attachFromPath(
                    $attachment['path'],
                    $attachment['name'] ?? basename($attachment['path']),
                    $attachment['contentType'] ?? null
                );
            }
        }

        $this->mailer->send($email);
    }
}