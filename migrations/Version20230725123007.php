<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230725123007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE caisse (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, products_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_3AF346686C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, transactions_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, INDEX IDX_62534E2177E1607F (transactions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, cash_total DOUBLE PRECISION NOT NULL, card_total DOUBLE PRECISION NOT NULL, cheque_total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, stock_quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_iteme (id INT AUTO_INCREMENT NOT NULL, transaction_iteme_id INT DEFAULT NULL, quantity INT NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_B4F98C5C50B08FAF (transaction_iteme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_iteme_products (transaction_iteme_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_7D3513A450B08FAF (transaction_iteme_id), INDEX IDX_7D3513A46C8A81A9 (products_id), PRIMARY KEY(transaction_iteme_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, transactions_date DATETIME NOT NULL, total_amount DOUBLE PRECISION NOT NULL, cash_amount DOUBLE PRECISION DEFAULT NULL, card_amount DOUBLE PRECISION DEFAULT NULL, cheque_amount DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF346686C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E2177E1607F FOREIGN KEY (transactions_id) REFERENCES transactions (id)');
        $this->addSql('ALTER TABLE transaction_iteme ADD CONSTRAINT FK_B4F98C5C50B08FAF FOREIGN KEY (transaction_iteme_id) REFERENCES transaction_iteme (id)');
        $this->addSql('ALTER TABLE transaction_iteme_products ADD CONSTRAINT FK_7D3513A450B08FAF FOREIGN KEY (transaction_iteme_id) REFERENCES transaction_iteme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_iteme_products ADD CONSTRAINT FK_7D3513A46C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF346686C8A81A9');
        $this->addSql('ALTER TABLE customers DROP FOREIGN KEY FK_62534E2177E1607F');
        $this->addSql('ALTER TABLE transaction_iteme DROP FOREIGN KEY FK_B4F98C5C50B08FAF');
        $this->addSql('ALTER TABLE transaction_iteme_products DROP FOREIGN KEY FK_7D3513A450B08FAF');
        $this->addSql('ALTER TABLE transaction_iteme_products DROP FOREIGN KEY FK_7D3513A46C8A81A9');
        $this->addSql('DROP TABLE caisse');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE transaction_iteme');
        $this->addSql('DROP TABLE transaction_iteme_products');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
