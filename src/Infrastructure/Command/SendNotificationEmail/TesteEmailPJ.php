<?php

namespace Infrastructure\Command\SendNotificationEmail;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Infrastructure\Symfony\Services\Email\EmailSenderInterface;
#[AsCommand(
    name: 'app:teste-email-pj',
    description: 'Test email sending for PJ'
)]

class TesteEmailPJ  extends Command
{
    
    public function __construct(
        private readonly EmailSenderInterface $emailSender,
        private readonly string $noReplyEmail,
        private readonly string $maintenanceNotificationFile,
    )
    {
        parent::__construct('app:teste-email-pj');
    }

    protected function configure(): void
    {
        $this->setDescription('Test email sending for PJ');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //invite user to write his email for test
        $output->writeln('<info>Please enter the email address you want to test:</info>');
        $email = readline('Email: ');
        while (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $output->writeln('<error>Invalid email address. Please try again.</error>');
            $email = readline('Email: ');
        }
        
        $output->writeln(sprintf('<info>Testing email sending to: %s</info>', $email));
        $output->writeln('<info>Testing email sending for PJ...</info>');
        // Simulate email sending logic here

        $this->emailSender->sendEmail(
            'email/pj/pj.html.twig',
            [
                'name' => 'John Doe',
                'email' => $email,
                'content' => 'This is a test email for PJ.',
            ],
            'Test Email Subject',
            $this->noReplyEmail,
            [$email],
            [],
            [
                [
                    'path' => $this->maintenanceNotificationFile,
                    'name' => 'attachment.pdf',
                    'contentType' => 'application/pdf',
                ],
            ]

        );
        $output->writeln('<info>Email sent successfully!</info>');

        return Command::SUCCESS;
    }
}