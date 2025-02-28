<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226082355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_A2B072885E237E06 ON documents');
        $this->addSql('DROP INDEX UNIQ_A2B07288841CB121 ON documents');
        $this->addSql('DROP INDEX UNIQ_A2B07288F7C0246A ON documents');
        $this->addSql('ALTER TABLE documents ADD type VARCHAR(180) NOT NULL');
      
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP type');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2B072885E237E06 ON documents (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2B07288841CB121 ON documents (uri)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2B07288F7C0246A ON documents (size)');
    }
}
