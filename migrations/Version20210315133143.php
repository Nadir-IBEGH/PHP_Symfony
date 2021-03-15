<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315133143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_item DROP FOREIGN KEY FK_C101DF364584665A');
        $this->addSql('ALTER TABLE purchase_item DROP FOREIGN KEY FK_C101DF36558FBEB9');
        $this->addSql('DROP INDEX idx_c101df364584665a ON purchase_item');
        $this->addSql('CREATE INDEX IDX_6FA8ED7D4584665A ON purchase_item (product_id)');
        $this->addSql('DROP INDEX idx_c101df36558fbeb9 ON purchase_item');
        $this->addSql('CREATE INDEX IDX_6FA8ED7D558FBEB9 ON purchase_item (purchase_id)');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_C101DF364584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_C101DF36558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_item DROP FOREIGN KEY FK_6FA8ED7D4584665A');
        $this->addSql('ALTER TABLE purchase_item DROP FOREIGN KEY FK_6FA8ED7D558FBEB9');
        $this->addSql('DROP INDEX idx_6fa8ed7d4584665a ON purchase_item');
        $this->addSql('CREATE INDEX IDX_C101DF364584665A ON purchase_item (product_id)');
        $this->addSql('DROP INDEX idx_6fa8ed7d558fbeb9 ON purchase_item');
        $this->addSql('CREATE INDEX IDX_C101DF36558FBEB9 ON purchase_item (purchase_id)');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_6FA8ED7D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_6FA8ED7D558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id)');
        $this->addSql('ALTER TABLE user DROP reset_token, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
