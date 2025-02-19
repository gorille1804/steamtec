<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219080235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chantiers (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) DEFAULT NULL, machine_id VARCHAR(255) DEFAULT NULL, name VARCHAR(180) NOT NULL, description VARCHAR(255) NOT NULL, hours INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4FB3F7055E237E06 (name), INDEX IDX_4FB3F705A76ED395 (user_id), INDEX IDX_4FB3F705F6B75B26 (machine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chantiers ADD CONSTRAINT FK_4FB3F705A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE chantiers ADD CONSTRAINT FK_4FB3F705F6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chantiers DROP FOREIGN KEY FK_4FB3F705A76ED395');
        $this->addSql('ALTER TABLE chantiers DROP FOREIGN KEY FK_4FB3F705F6B75B26');
        $this->addSql('DROP TABLE chantiers');
    }
}
