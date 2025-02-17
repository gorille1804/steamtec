<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217145819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE machine ADD user_id INT DEFAULT NULL, ADD numero_identification VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD marque VARCHAR(255) NOT NULL, ADD temp_usage INT DEFAULT NULL, ADD seuil_maintenance INT NOT NULL');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF84A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1505DF84F5211ED ON machine (numero_identification)');
        $this->addSql('CREATE INDEX IDX_1505DF84A76ED395 ON machine (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE machine DROP FOREIGN KEY FK_1505DF84A76ED395');
        $this->addSql('DROP INDEX UNIQ_1505DF84F5211ED ON machine');
        $this->addSql('DROP INDEX IDX_1505DF84A76ED395 ON machine');
        $this->addSql('ALTER TABLE machine DROP user_id, DROP numero_identification, DROP nom, DROP marque, DROP temp_usage, DROP seuil_maintenance');
    }
}
