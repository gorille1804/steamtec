<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219092531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chantier_machines (id VARCHAR(255) NOT NULL, machine_id VARCHAR(255) DEFAULT NULL, chantier_id VARCHAR(255) DEFAULT NULL, INDEX IDX_3ACF582AF6B75B26 (machine_id), INDEX IDX_3ACF582AD0C0049D (chantier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chantier_machines ADD CONSTRAINT FK_3ACF582AF6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id)');
        $this->addSql('ALTER TABLE chantier_machines ADD CONSTRAINT FK_3ACF582AD0C0049D FOREIGN KEY (chantier_id) REFERENCES chantiers (id)');
        $this->addSql('ALTER TABLE chantiers DROP FOREIGN KEY FK_4FB3F705F6B75B26');
        $this->addSql('DROP INDEX IDX_4FB3F705F6B75B26 ON chantiers');
        $this->addSql('ALTER TABLE chantiers DROP machine_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chantier_machines DROP FOREIGN KEY FK_3ACF582AF6B75B26');
        $this->addSql('ALTER TABLE chantier_machines DROP FOREIGN KEY FK_3ACF582AD0C0049D');
        $this->addSql('DROP TABLE chantier_machines');
        $this->addSql('ALTER TABLE chantiers ADD machine_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE chantiers ADD CONSTRAINT FK_4FB3F705F6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4FB3F705F6B75B26 ON chantiers (machine_id)');
    }
}
