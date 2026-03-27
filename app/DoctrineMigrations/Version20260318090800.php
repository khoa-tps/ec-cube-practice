<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318090800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plg_coupon ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD CONSTRAINT FK_755A31039395C3F3 FOREIGN KEY (customer_id) REFERENCES dtb_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_755A31039395C3F3 ON plg_coupon (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE plg_coupon DROP CONSTRAINT FK_755A31039395C3F3');
        $this->addSql('DROP INDEX IDX_755A31039395C3F3');
        $this->addSql('ALTER TABLE plg_coupon DROP customer_id');
    }
}
