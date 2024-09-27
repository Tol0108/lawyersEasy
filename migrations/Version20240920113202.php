<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240920113202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat DROP INDEX fk_avocat_user, ADD UNIQUE INDEX UNIQ_6A215DCA76ED395 (user_id)');
        // $this->addSql('ALTER TABLE avocat DROP FOREIGN KEY fk_avocat_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avocat DROP INDEX UNIQ_6A215DCA76ED395, ADD INDEX fk_avocat_user (user_id)');
        $this->addSql('ALTER TABLE avocat ADD CONSTRAINT fk_avocat_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }
}
