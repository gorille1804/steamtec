<?php
namespace Infrastructure\Symfony\Services\Email;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Environment;
use Symfony\Component\Mailer\Transport\TransportInterface;

class DefaultEmailSender implements EmailSenderInterface
{
    public function __construct(
        private readonly TransportInterface $transport,
        private readonly Environment $twig,
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
        try {
            $htmlContent = $this->twig->render($templatePath, $context);

            $email = new Email();
            $email
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

            $this->transport->send($email);
        } catch (TransportExceptionInterface $e) {
            error_log('Transport error: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            error_log('Email error: ' . $e->getMessage());
            throw $e;
        }
    }
}