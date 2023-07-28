<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728164103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction_iteme_transactions (transaction_iteme_id INT NOT NULL, transactions_id INT NOT NULL, INDEX IDX_339DA6A450B08FAF (transaction_iteme_id), INDEX IDX_339DA6A477E1607F (transactions_id), PRIMARY KEY(transaction_iteme_id, transactions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactions_products (transactions_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_8B3C672777E1607F (transactions_id), INDEX IDX_8B3C67276C8A81A9 (products_id), PRIMARY KEY(transactions_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction_iteme_transactions ADD CONSTRAINT FK_339DA6A450B08FAF FOREIGN KEY (transaction_iteme_id) REFERENCES transaction_iteme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_iteme_transactions ADD CONSTRAINT FK_339DA6A477E1607F FOREIGN KEY (transactions_id) REFERENCES transactions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transactions_products ADD CONSTRAINT FK_8B3C672777E1607F FOREIGN KEY (transactions_id) REFERENCES transactions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transactions_products ADD CONSTRAINT FK_8B3C67276C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transactions ADD caisse_id INT DEFAULT NULL, CHANGE transactions_date transactions_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C27B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisse (id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C27B4FEBF ON transactions (caisse_id)');
        $this->addSql('ALTER TABLE user ADD caisse_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64927B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisse (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64927B4FEBF ON user (caisse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction_iteme_transactions DROP FOREIGN KEY FK_339DA6A450B08FAF');
        $this->addSql('ALTER TABLE transaction_iteme_transactions DROP FOREIGN KEY FK_339DA6A477E1607F');
        $this->addSql('ALTER TABLE transactions_products DROP FOREIGN KEY FK_8B3C672777E1607F');
        $this->addSql('ALTER TABLE transactions_products DROP FOREIGN KEY FK_8B3C67276C8A81A9');
        $this->addSql('DROP TABLE transaction_iteme_transactions');
        $this->addSql('DROP TABLE transactions_products');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C27B4FEBF');
        $this->addSql('DROP INDEX IDX_EAA81A4C27B4FEBF ON transactions');
        $this->addSql('ALTER TABLE transactions DROP caisse_id, CHANGE transactions_date transactions_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64927B4FEBF');
        $this->addSql('DROP INDEX UNIQ_8D93D64927B4FEBF ON user');
        $this->addSql('ALTER TABLE user DROP caisse_id');
    }
}
