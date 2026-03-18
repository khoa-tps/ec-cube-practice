<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313023416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dtb_features_group (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, publish_date_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, publish_date_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN dtb_features_group.publish_date_from IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN dtb_features_group.publish_date_to IS \'(DC2Type:datetime)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE dtb_features_group');
    }
}
