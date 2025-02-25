<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225113928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE machine_logs ADD chantier_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE machine_logs ADD CONSTRAINT FK_A6852D25D0C0049D FOREIGN KEY (chantier_id) REFERENCES chantiers (id)');
        $this->addSql('CREATE INDEX IDX_A6852D25D0C0049D ON machine_logs (chantier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE machine_logs DROP FOREIGN KEY FK_A6852D25D0C0049D');
        $this->addSql('DROP INDEX IDX_A6852D25D0C0049D ON machine_logs');
        $this->addSql('ALTER TABLE machine_logs DROP chantier_id');
    }
}
