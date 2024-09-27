<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240920102002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD code_postal VARCHAR(255) DEFAULT NULL, ADD licence_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users DROP licence_number');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat DROP nom, DROP prenom, DROP code_postal, DROP licence_number');
        $this->addSql('ALTER TABLE users ADD licence_number VARCHAR(255) DEFAULT NULL');
    }
}
