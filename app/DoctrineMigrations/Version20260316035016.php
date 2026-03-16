<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316035016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plg_yamato_order (id SERIAL NOT NULL, order_id INT DEFAULT NULL, memo01 TEXT DEFAULT NULL, memo02 TEXT DEFAULT NULL, memo03 TEXT DEFAULT NULL, memo04 TEXT DEFAULT NULL, memo05 TEXT DEFAULT NULL, memo06 TEXT DEFAULT NULL, memo07 TEXT DEFAULT NULL, memo08 TEXT DEFAULT NULL, memo09 TEXT DEFAULT NULL, memo10 TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2F73469D8D9F6D38 ON plg_yamato_order (order_id)');
        $this->addSql('CREATE TABLE plg_yamato_payment_method (id SERIAL NOT NULL, payment_id INT DEFAULT NULL, payment_method TEXT NOT NULL, create_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, update_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, memo01 TEXT DEFAULT NULL, memo02 TEXT DEFAULT NULL, memo03 TEXT DEFAULT NULL, memo04 TEXT DEFAULT NULL, memo05 TEXT DEFAULT NULL, memo06 TEXT DEFAULT NULL, memo07 TEXT DEFAULT NULL, memo08 TEXT DEFAULT NULL, memo09 TEXT DEFAULT NULL, memo10 TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B0B15714C3A3BB ON plg_yamato_payment_method (payment_id)');
        $this->addSql('COMMENT ON COLUMN plg_yamato_payment_method.create_date IS \'(DC2Type:datetimetz)\'');
        $this->addSql('COMMENT ON COLUMN plg_yamato_payment_method.update_date IS \'(DC2Type:datetimetz)\'');
        $this->addSql('CREATE TABLE plg_yamato_plugin (id SERIAL NOT NULL, plugin_code VARCHAR(255) DEFAULT NULL, plugin_name VARCHAR(255) DEFAULT NULL, sub_data TEXT DEFAULT NULL, b2_data TEXT DEFAULT NULL, auto_update_flg INT NOT NULL, del_flg INT NOT NULL, create_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, update_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN plg_yamato_plugin.create_date IS \'(DC2Type:datetimetz)\'');
        $this->addSql('COMMENT ON COLUMN plg_yamato_plugin.update_date IS \'(DC2Type:datetimetz)\'');
        $this->addSql('CREATE TABLE yamato_payment_status (id SMALLINT NOT NULL, name VARCHAR(255) NOT NULL, sort_no SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE plg_yamato_order ADD CONSTRAINT FK_2F73469D8D9F6D38 FOREIGN KEY (order_id) REFERENCES dtb_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE plg_yamato_payment_method ADD CONSTRAINT FK_4B0B15714C3A3BB FOREIGN KEY (payment_id) REFERENCES dtb_payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dtb_order DROP scheduled_shipping_date');
        $this->addSql('ALTER TABLE dtb_shipping DROP pre_tracking_number');
        $this->addSql('ALTER TABLE dtb_shipping DROP tracking_information');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE plg_yamato_order DROP CONSTRAINT FK_2F73469D8D9F6D38');
        $this->addSql('ALTER TABLE plg_yamato_payment_method DROP CONSTRAINT FK_4B0B15714C3A3BB');
        $this->addSql('DROP TABLE plg_yamato_order');
        $this->addSql('DROP TABLE plg_yamato_payment_method');
        $this->addSql('DROP TABLE plg_yamato_plugin');
        $this->addSql('DROP TABLE yamato_payment_status');
        $this->addSql('ALTER TABLE dtb_order ADD scheduled_shipping_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE dtb_shipping ADD pre_tracking_number VARCHAR(12) DEFAULT NULL');
        $this->addSql('ALTER TABLE dtb_shipping ADD tracking_information VARCHAR(255) DEFAULT NULL');
    }
}
