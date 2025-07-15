<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250427180000_add_is_year_to_entretien_logs extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute la colonne is_year (booléen) à la table entretien_logs';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE entretien_logs ADD is_year TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE entretien_logs DROP is_year');
    }
} 