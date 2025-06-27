<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324132630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance_notifications ADD parch_machine_id VARCHAR(255) NOT NULL, CHANGE type type  ENUM(\'maintenance_notification_regular\', \'maintenance_notification_timely\', \'maintenance_notification_emergency\')  NOT NULL');
        $this->addSql('ALTER TABLE maintenance_notifications ADD CONSTRAINT FK_5890CA06A005A77B FOREIGN KEY (parch_machine_id) REFERENCES parc_machine (id)');
        $this->addSql('CREATE INDEX IDX_5890CA06A005A77B ON maintenance_notifications (parch_machine_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance_notifications DROP FOREIGN KEY FK_5890CA06A005A77B');
        $this->addSql('DROP INDEX IDX_5890CA06A005A77B ON maintenance_notifications');
        $this->addSql('ALTER TABLE maintenance_notifications DROP parch_machine_id, CHANGE type type VARCHAR(255) NOT NULL');
    }
}
