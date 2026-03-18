<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316095136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dtb_inquiry ADD inquiry_sub_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dtb_inquiry ADD CONSTRAINT FK_4DEEE5722ED7D1A7 FOREIGN KEY (inquiry_sub_category_id) REFERENCES dtb_inquiry_sub_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4DEEE5722ED7D1A7 ON dtb_inquiry (inquiry_sub_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dtb_inquiry DROP CONSTRAINT FK_4DEEE5722ED7D1A7');
        $this->addSql('DROP INDEX IDX_4DEEE5722ED7D1A7');
        $this->addSql('ALTER TABLE dtb_inquiry DROP inquiry_sub_category_id');
    }
}
