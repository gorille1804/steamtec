<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour ajouter les nouveaux champs au formulaire de chantier
 */
final class Version20250327120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des nouveaux champs pour le formulaire de chantier amélioré';
    }

    public function up(Schema $schema): void
    {
        // Ajout des nouveaux champs à la table chantiers avec vérification d'existence
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS machine_serial_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS chantier_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS surface DECIMAL(10,2) NOT NULL');
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS duration DECIMAL(5,1) NOT NULL');
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS rendement DECIMAL(10,2) NOT NULL');
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS surface_types VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS materials JSON NOT NULL');
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS encrassement_level INT NOT NULL');
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS vetuste_level INT NOT NULL');
        $this->addSql('ALTER TABLE chantiers ADD COLUMN IF NOT EXISTS commentaire LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // Suppression des nouveaux champs
        $this->addSql('ALTER TABLE chantiers DROP machine_serial_number');
        $this->addSql('ALTER TABLE chantiers DROP chantier_date');
        $this->addSql('ALTER TABLE chantiers DROP surface');
        $this->addSql('ALTER TABLE chantiers DROP duration');
        $this->addSql('ALTER TABLE chantiers DROP rendement');
        $this->addSql('ALTER TABLE chantiers DROP surface_types');
        $this->addSql('ALTER TABLE chantiers DROP materials');
        $this->addSql('ALTER TABLE chantiers DROP encrassement_level');
        $this->addSql('ALTER TABLE chantiers DROP vetuste_level');
        $this->addSql('ALTER TABLE chantiers DROP commentaire');
    }
} 