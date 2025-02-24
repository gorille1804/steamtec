<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224082111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chantier_machines DROP FOREIGN KEY FK_3ACF582AF6B75B26');
        $this->addSql('DROP INDEX IDX_3ACF582AF6B75B26 ON chantier_machines');
        $this->addSql('ALTER TABLE chantier_machines CHANGE machine_id parc_machine_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE chantier_machines ADD CONSTRAINT FK_3ACF582ABB42DA91 FOREIGN KEY (parc_machine_id) REFERENCES parc_machine (id)');
        $this->addSql('CREATE INDEX IDX_3ACF582ABB42DA91 ON chantier_machines (parc_machine_id)');
        $this->addSql('ALTER TABLE chantiers DROP hours');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chantier_machines DROP FOREIGN KEY FK_3ACF582ABB42DA91');
        $this->addSql('DROP INDEX IDX_3ACF582ABB42DA91 ON chantier_machines');
        $this->addSql('ALTER TABLE chantier_machines CHANGE parc_machine_id machine_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE chantier_machines ADD CONSTRAINT FK_3ACF582AF6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3ACF582AF6B75B26 ON chantier_machines (machine_id)');
        $this->addSql('ALTER TABLE chantiers ADD hours INT NOT NULL');
    }
}
