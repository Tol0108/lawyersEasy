<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924144739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations ADD avocat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239EDBF2DB2 FOREIGN KEY (avocat_id) REFERENCES avocat (id)');
        $this->addSql('CREATE INDEX IDX_4DA239EDBF2DB2 ON reservations (avocat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239EDBF2DB2');
        $this->addSql('DROP INDEX IDX_4DA239EDBF2DB2 ON reservations');
        $this->addSql('ALTER TABLE reservations DROP avocat_id');
    }
}
