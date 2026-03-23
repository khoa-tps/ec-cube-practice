<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260323081121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plg_coupon ADD term_usage_period INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD term_usage_period_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD term_usage_period_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD term_available_count INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD term_available_cycle_cycle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD term_available_cycle_count INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD term_minimun_spend_amount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD term_shop_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD term_category_id INT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN plg_coupon.term_usage_period_from IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN plg_coupon.term_usage_period_to IS \'(DC2Type:datetime)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE plg_coupon DROP term_usage_period');
        $this->addSql('ALTER TABLE plg_coupon DROP term_usage_period_from');
        $this->addSql('ALTER TABLE plg_coupon DROP term_usage_period_to');
        $this->addSql('ALTER TABLE plg_coupon DROP term_available_count');
        $this->addSql('ALTER TABLE plg_coupon DROP term_available_cycle_cycle');
        $this->addSql('ALTER TABLE plg_coupon DROP term_available_cycle_count');
        $this->addSql('ALTER TABLE plg_coupon DROP term_minimun_spend_amount');
        $this->addSql('ALTER TABLE plg_coupon DROP term_shop_id');
        $this->addSql('ALTER TABLE plg_coupon DROP term_category_id');
    }
}
