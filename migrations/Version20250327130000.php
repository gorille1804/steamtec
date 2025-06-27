<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour permettre les valeurs NULL dans la colonne description
 */
final class Version20250327130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Permettre les valeurs NULL dans la colonne description de la table chantiers';
    }

    public function up(Schema $schema): void
    {
        // Modifier la colonne description pour permettre NULL
        $this->addSql('ALTER TABLE chantiers MODIFY description TEXT NULL');
    }

    public function down(Schema $schema): void
    {
        // Remettre la colonne description comme NOT NULL
        $this->addSql('ALTER TABLE chantiers MODIFY description TEXT NOT NULL');
    }
} 