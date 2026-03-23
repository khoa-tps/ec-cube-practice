<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260323025734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plg_coupon ADD issue_type INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD issue_type_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD issue_type_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD issue_type_user_ids JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD shop_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD category_id INT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN plg_coupon.issue_type_from IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN plg_coupon.issue_type_to IS \'(DC2Type:datetime)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE plg_coupon DROP issue_type');
        $this->addSql('ALTER TABLE plg_coupon DROP issue_type_from');
        $this->addSql('ALTER TABLE plg_coupon DROP issue_type_to');
        $this->addSql('ALTER TABLE plg_coupon DROP issue_type_user_ids');
        $this->addSql('ALTER TABLE plg_coupon DROP shop_id');
        $this->addSql('ALTER TABLE plg_coupon DROP category_id');
    }
}
