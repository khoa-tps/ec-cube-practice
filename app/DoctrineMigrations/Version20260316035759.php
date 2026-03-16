<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316035759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dtb_inquiry (id SERIAL NOT NULL, user_id INT NOT NULL, email VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, detail TEXT NOT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN dtb_inquiry.created_at IS \'(DC2Type:datetime)\'');
        $this->addSql('CREATE TABLE dtb_inquiry_category (id SERIAL NOT NULL, parent_id INT NOT NULL, name VARCHAR(255) NOT NULL, sort_no INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN dtb_inquiry_category.created_at IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN dtb_inquiry_category.updated_at IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN dtb_inquiry_category.deleted_at IS \'(DC2Type:datetime)\'');
        $this->addSql('CREATE TABLE dtb_inquiry_sub_category (id SERIAL NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, sort_no INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN dtb_inquiry_sub_category.created_at IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN dtb_inquiry_sub_category.updated_at IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN dtb_inquiry_sub_category.deleted_at IS \'(DC2Type:datetime)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE dtb_inquiry');
        $this->addSql('DROP TABLE dtb_inquiry_category');
        $this->addSql('DROP TABLE dtb_inquiry_sub_category');
    }
}
