<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226081549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE documents (id VARCHAR(255) NOT NULL, name VARCHAR(180) NOT NULL, uri VARCHAR(180) NOT NULL, size VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A2B072885E237E06 (name), UNIQUE INDEX UNIQ_A2B07288841CB121 (uri), UNIQUE INDEX UNIQ_A2B07288F7C0246A (size), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
       
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE documents');   
    }
}
