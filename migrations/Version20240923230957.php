<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923230957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F7A53052A');
        $this->addSql('DROP INDEX IDX_2CBACE2F7A53052A ON disponibilite');
        $this->addSql('ALTER TABLE disponibilite ADD start DATETIME NOT NULL, ADD end DATETIME NOT NULL, DROP start_date_time, CHANGE legal_advisor_id avocat_id INT NOT NULL');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2FEDBF2DB2 FOREIGN KEY (avocat_id) REFERENCES avocat (id)');
        $this->addSql('CREATE INDEX IDX_2CBACE2FEDBF2DB2 ON disponibilite (avocat_id)');
        $this->addSql('ALTER TABLE users ADD licence_number VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2FEDBF2DB2');
        $this->addSql('DROP INDEX IDX_2CBACE2FEDBF2DB2 ON disponibilite');
        $this->addSql('ALTER TABLE disponibilite ADD start_date_time DATETIME DEFAULT NULL, DROP start, DROP end, CHANGE avocat_id legal_advisor_id INT NOT NULL');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F7A53052A FOREIGN KEY (legal_advisor_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_2CBACE2F7A53052A ON disponibilite (legal_advisor_id)');
        $this->addSql('ALTER TABLE users DROP licence_number');
    }
}
