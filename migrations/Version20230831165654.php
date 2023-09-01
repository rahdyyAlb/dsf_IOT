<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230831165654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction_iteme DROP FOREIGN KEY FK_B4F98C5C50B08FAF');
        $this->addSql('ALTER TABLE transaction_iteme_products DROP FOREIGN KEY FK_7D3513A450B08FAF');
        $this->addSql('ALTER TABLE transaction_iteme_products DROP FOREIGN KEY FK_7D3513A46C8A81A9');
        $this->addSql('ALTER TABLE transaction_iteme_transactions DROP FOREIGN KEY FK_339DA6A477E1607F');
        $this->addSql('ALTER TABLE transaction_iteme_transactions DROP FOREIGN KEY FK_339DA6A450B08FAF');
        $this->addSql('DROP TABLE transaction_iteme');
        $this->addSql('DROP TABLE transaction_iteme_products');
        $this->addSql('DROP TABLE transaction_iteme_transactions');
        $this->addSql('ALTER TABLE transactions_products DROP FOREIGN KEY FK_8B3C672777E1607F');
        $this->addSql('ALTER TABLE transactions_products DROP FOREIGN KEY FK_8B3C67276C8A81A9');
        $this->addSql('DROP INDEX IDX_8B3C67276C8A81A9 ON transactions_products');
        $this->addSql('DROP INDEX IDX_8B3C672777E1607F ON transactions_products');
        $this->addSql('ALTER TABLE transactions_products ADD id INT AUTO_INCREMENT NOT NULL, ADD transaction_id INT DEFAULT NULL, ADD product_id INT DEFAULT NULL, ADD quantity INT NOT NULL, DROP transactions_id, DROP products_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE transactions_products ADD CONSTRAINT FK_8B3C67272FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transactions (id)');
        $this->addSql('ALTER TABLE transactions_products ADD CONSTRAINT FK_8B3C67274584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_8B3C67272FC0CB0F ON transactions_products (transaction_id)');
        $this->addSql('CREATE INDEX IDX_8B3C67274584665A ON transactions_products (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction_iteme (id INT AUTO_INCREMENT NOT NULL, transaction_iteme_id INT DEFAULT NULL, quantity INT NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_B4F98C5C50B08FAF (transaction_iteme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE transaction_iteme_products (transaction_iteme_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_7D3513A450B08FAF (transaction_iteme_id), INDEX IDX_7D3513A46C8A81A9 (products_id), PRIMARY KEY(transaction_iteme_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE transaction_iteme_transactions (transaction_iteme_id INT NOT NULL, transactions_id INT NOT NULL, INDEX IDX_339DA6A477E1607F (transactions_id), INDEX IDX_339DA6A450B08FAF (transaction_iteme_id), PRIMARY KEY(transaction_iteme_id, transactions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE transaction_iteme ADD CONSTRAINT FK_B4F98C5C50B08FAF FOREIGN KEY (transaction_iteme_id) REFERENCES transaction_iteme (id)');
        $this->addSql('ALTER TABLE transaction_iteme_products ADD CONSTRAINT FK_7D3513A450B08FAF FOREIGN KEY (transaction_iteme_id) REFERENCES transaction_iteme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_iteme_products ADD CONSTRAINT FK_7D3513A46C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_iteme_transactions ADD CONSTRAINT FK_339DA6A477E1607F FOREIGN KEY (transactions_id) REFERENCES transactions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_iteme_transactions ADD CONSTRAINT FK_339DA6A450B08FAF FOREIGN KEY (transaction_iteme_id) REFERENCES transaction_iteme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transactions_products MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE transactions_products DROP FOREIGN KEY FK_8B3C67272FC0CB0F');
        $this->addSql('ALTER TABLE transactions_products DROP FOREIGN KEY FK_8B3C67274584665A');
        $this->addSql('DROP INDEX IDX_8B3C67272FC0CB0F ON transactions_products');
        $this->addSql('DROP INDEX IDX_8B3C67274584665A ON transactions_products');
        $this->addSql('DROP INDEX `PRIMARY` ON transactions_products');
        $this->addSql('ALTER TABLE transactions_products ADD products_id INT NOT NULL, DROP id, DROP transaction_id, DROP product_id, CHANGE quantity transactions_id INT NOT NULL');
        $this->addSql('ALTER TABLE transactions_products ADD CONSTRAINT FK_8B3C672777E1607F FOREIGN KEY (transactions_id) REFERENCES transactions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transactions_products ADD CONSTRAINT FK_8B3C67276C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_8B3C67276C8A81A9 ON transactions_products (products_id)');
        $this->addSql('CREATE INDEX IDX_8B3C672777E1607F ON transactions_products (transactions_id)');
        $this->addSql('ALTER TABLE transactions_products ADD PRIMARY KEY (transactions_id, products_id)');
    }
}
