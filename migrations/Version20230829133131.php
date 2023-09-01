<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230829133131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF21AD5CDBF');
        $this->addSql('DROP INDEX IDX_24CC0DF21AD5CDBF ON panier');
        $this->addSql('ALTER TABLE panier ADD user_id INT DEFAULT NULL, CHANGE cart_id products_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF26C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF26C8A81A9 ON panier (products_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF2A76ED395 ON panier (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF26C8A81A9');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('DROP INDEX IDX_24CC0DF26C8A81A9 ON panier');
        $this->addSql('DROP INDEX IDX_24CC0DF2A76ED395 ON panier');
        $this->addSql('ALTER TABLE panier ADD cart_id INT DEFAULT NULL, DROP products_id, DROP user_id');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF21AD5CDBF FOREIGN KEY (cart_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF21AD5CDBF ON panier (cart_id)');
    }
}
