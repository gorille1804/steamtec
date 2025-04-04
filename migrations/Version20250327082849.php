<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327082849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE push_notifications (id VARCHAR(255) NOT NULL, receiver VARCHAR(255) NOT NULL, type  ENUM(\'maintenance_notification_regular\', \'maintenance_notification_timely\', \'maintenance_notification_emergency\')  NOT NULL, message VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5B9B7E4F3DB88C96 (receiver), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE push_notifications ADD CONSTRAINT FK_5B9B7E4F3DB88C96 FOREIGN KEY (receiver) REFERENCES users (id)');
       }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE push_notifications DROP FOREIGN KEY FK_5B9B7E4F3DB88C96');
        $this->addSql('DROP TABLE push_notifications');
     }
}
