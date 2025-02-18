<?php

namespace Infrastructure\Symfony\Services\Email;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;


class EmailSenderFactory
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private ParameterBagInterface $parameterBag,
        private ?string $mailerService=null
    ) {}

    public function createEmailSender(): EmailSenderInterface
    {
        $mailerService = $this->mailerService ?? null;

        return match ($mailerService) {
            default => new DefaultEmailSender($this->mailer, $this->twig),
        };
    }
}