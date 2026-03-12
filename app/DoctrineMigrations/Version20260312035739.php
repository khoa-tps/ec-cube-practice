<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260312035739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dtb_features (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, catchphrase VARCHAR(255) NOT NULL, description TEXT NOT NULL, publish_date_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, publish_date_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status SMALLINT NOT NULL, related_category_ids TEXT DEFAULT NULL, keywords TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN dtb_features.publish_date_from IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN dtb_features.publish_date_to IS \'(DC2Type:datetime)\'');
        $this->addSql('COMMENT ON COLUMN dtb_features.related_category_ids IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN dtb_features.keywords IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE dtb_features');
    }
}
