<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240914131842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_6A215DCE7927C74 ON avocat');
        $this->addSql('ALTER TABLE avocat DROP nom, DROP prenom, DROP email');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E92195E0F0');
        $this->addSql('DROP INDEX IDX_1483A5E92195E0F0 ON users');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A215DCE7927C74 ON avocat (email)');
        $this->addSql('ALTER TABLE users ADD specialite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E92195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E92195E0F0 ON users (specialite_id)');
    }
}
