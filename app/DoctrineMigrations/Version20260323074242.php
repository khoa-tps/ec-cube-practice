<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260323074242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plg_coupon ADD issue_trigger INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD issuance_period_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD issuance_period_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD issuance_shop_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD issuance_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD issuance_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plg_coupon ADD issuance_display INT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN plg_coupon.issuance_period_from IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN plg_coupon.issuance_period_to IS \'(DC2Type:datetime)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE plg_coupon DROP issue_trigger');
        $this->addSql('ALTER TABLE plg_coupon DROP issuance_period_from');
        $this->addSql('ALTER TABLE plg_coupon DROP issuance_period_to');
        $this->addSql('ALTER TABLE plg_coupon DROP issuance_shop_id');
        $this->addSql('ALTER TABLE plg_coupon DROP issuance_product_id');
        $this->addSql('ALTER TABLE plg_coupon DROP issuance_category_id');
        $this->addSql('ALTER TABLE plg_coupon DROP issuance_display');
    }
}
