<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727082045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction_iteme_transactions (transaction_iteme_id INT NOT NULL, transactions_id INT NOT NULL, INDEX IDX_339DA6A450B08FAF (transaction_iteme_id), INDEX IDX_339DA6A477E1607F (transactions_id), PRIMARY KEY(transaction_iteme_id, transactions_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction_iteme_transactions ADD CONSTRAINT FK_339DA6A450B08FAF FOREIGN KEY (transaction_iteme_id) REFERENCES transaction_iteme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_iteme_transactions ADD CONSTRAINT FK_339DA6A477E1607F FOREIGN KEY (transactions_id) REFERENCES transactions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction_iteme_transactions DROP FOREIGN KEY FK_339DA6A450B08FAF');
        $this->addSql('ALTER TABLE transaction_iteme_transactions DROP FOREIGN KEY FK_339DA6A477E1607F');
        $this->addSql('DROP TABLE transaction_iteme_transactions');
    }
}
