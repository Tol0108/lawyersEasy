<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924023959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat DROP FOREIGN KEY fk_avocat_user');
        $this->addSql('DROP INDEX UNIQ_6A215DCA76ED395 ON avocat');
        $this->addSql('ALTER TABLE avocat ADD description LONGTEXT DEFAULT NULL, DROP user_id, DROP licence_number');
        $this->addSql('ALTER TABLE avocat ADD CONSTRAINT FK_6A215DC2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_6A215DC2195E0F0 ON avocat (specialite_id)');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2FEDBF2DB2');
        $this->addSql('ALTER TABLE disponibilite ADD date DATETIME NOT NULL, DROP start, DROP end, CHANGE avocat_id avocat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2FEDBF2DB2 FOREIGN KEY (avocat_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2397A53052A');
        $this->addSql('DROP INDEX IDX_4DA2397A53052A ON reservations');
        $this->addSql('ALTER TABLE reservations ADD updated_at DATETIME DEFAULT NULL, DROP legal_advisor_id, DROP status');


        $this->addSql('CREATE INDEX IDX_1483A5E92195E0F0 ON users (specialite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat DROP FOREIGN KEY FK_6A215DC2195E0F0');
        $this->addSql('DROP INDEX IDX_6A215DC2195E0F0 ON avocat');
        $this->addSql('ALTER TABLE avocat ADD user_id INT NOT NULL, ADD licence_number VARCHAR(255) DEFAULT NULL, DROP description');
        $this->addSql('ALTER TABLE avocat ADD CONSTRAINT fk_avocat_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A215DCA76ED395 ON avocat (user_id)');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2FEDBF2DB2');
        $this->addSql('ALTER TABLE disponibilite ADD end DATETIME NOT NULL, CHANGE avocat_id avocat_id INT NOT NULL, CHANGE date start DATETIME NOT NULL');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2FEDBF2DB2 FOREIGN KEY (avocat_id) REFERENCES avocat (id)');
        $this->addSql('ALTER TABLE reservations ADD legal_advisor_id INT NOT NULL, ADD status VARCHAR(30) DEFAULT NULL, DROP updated_at');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2397A53052A FOREIGN KEY (legal_advisor_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_4DA2397A53052A ON reservations (legal_advisor_id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E92195E0F0');
        $this->addSql('DROP INDEX IDX_1483A5E92195E0F0 ON users');
        $this->addSql('ALTER TABLE users ADD licence_number VARCHAR(255) DEFAULT NULL, DROP specialite_id');
    }
}
