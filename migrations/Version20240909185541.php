<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909185541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avocat (id INT AUTO_INCREMENT NOT NULL, specialite_id INT DEFAULT NULL, user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, INDEX IDX_6A215DC2195E0F0 (specialite_id), UNIQUE INDEX UNIQ_6A215DCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avocat ADD CONSTRAINT FK_6A215DC2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE avocat ADD CONSTRAINT FK_6A215DCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat DROP FOREIGN KEY FK_6A215DC2195E0F0');
        $this->addSql('ALTER TABLE avocat DROP FOREIGN KEY FK_6A215DCA76ED395');
        $this->addSql('DROP TABLE avocat');
    }
}
