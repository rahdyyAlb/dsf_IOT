<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230725132914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day ADD caisse_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A02990EE70C79C FOREIGN KEY (caisse_id_id) REFERENCES caisse (id)');
        $this->addSql('CREATE INDEX IDX_E5A02990EE70C79C ON day (caisse_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A02990EE70C79C');
        $this->addSql('DROP INDEX IDX_E5A02990EE70C79C ON day');
        $this->addSql('ALTER TABLE day DROP caisse_id_id');
    }
}
