<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530161806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD specialite_id INT DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL, ADD licence_number VARCHAR(255) DEFAULT NULL, ADD is_verified TINYINT(1) NOT NULL, DROP login, DROP langue, DROP status, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E92195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E92195E0F0 ON users (specialite_id)');
        $this->addSql('ALTER TABLE users DROP langue, DROP status, DROP is_active');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E92195E0F0');
        $this->addSql('DROP INDEX IDX_1483A5E92195E0F0 ON users');
        $this->addSql('ALTER TABLE users ADD login VARCHAR(60) NOT NULL, ADD langue VARCHAR(2) DEFAULT NULL, ADD status VARCHAR(30) NOT NULL, DROP specialite_id, DROP adresse, DROP licence_number, DROP is_verified, CHANGE nom nom VARCHAR(60) NOT NULL, CHANGE prenom prenom VARCHAR(60) NOT NULL, CHANGE telephone telephone INT DEFAULT NULL, CHANGE roles roles JSON DEFAULT \'json_array()\' NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
