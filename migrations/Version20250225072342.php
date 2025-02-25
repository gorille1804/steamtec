<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225072342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE machine_logs (id VARCHAR(255) NOT NULL, parc_machine_id VARCHAR(255) NOT NULL, duration INT NOT NULL, log_date DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_A6852D25BB42DA91 (parc_machine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE machine_logs ADD CONSTRAINT FK_A6852D25BB42DA91 FOREIGN KEY (parc_machine_id) REFERENCES parc_machine (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE machine_logs DROP FOREIGN KEY FK_A6852D25BB42DA91');
        $this->addSql('DROP TABLE machine_logs');
    }
}
