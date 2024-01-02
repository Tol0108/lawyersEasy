<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207100504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat DROP FOREIGN KEY FK_6A215DC227BA24F');
        $this->addSql('DROP INDEX IDX_6A215DC227BA24F ON avocat');
        $this->addSql('ALTER TABLE avocat CHANGE avocatspecialite_id specialite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avocat ADD CONSTRAINT FK_6A215DC2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_6A215DC2195E0F0 ON avocat (specialite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat DROP FOREIGN KEY FK_6A215DC2195E0F0');
        $this->addSql('DROP INDEX IDX_6A215DC2195E0F0 ON avocat');
        $this->addSql('ALTER TABLE avocat CHANGE specialite_id avocatspecialite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avocat ADD CONSTRAINT FK_6A215DC227BA24F FOREIGN KEY (avocatspecialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_6A215DC227BA24F ON avocat (avocatspecialite_id)');
    }
}
