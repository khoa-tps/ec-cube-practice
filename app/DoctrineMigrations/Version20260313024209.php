<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313024209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dtb_features_group_link (id SERIAL NOT NULL, features_group_id INT NOT NULL, features_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8F851BA433EABFEB ON dtb_features_group_link (features_group_id)');
        $this->addSql('CREATE INDEX IDX_8F851BA4CEC89005 ON dtb_features_group_link (features_id)');
        $this->addSql('ALTER TABLE dtb_features_group_link ADD CONSTRAINT FK_8F851BA433EABFEB FOREIGN KEY (features_group_id) REFERENCES dtb_features_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dtb_features_group_link ADD CONSTRAINT FK_8F851BA4CEC89005 FOREIGN KEY (features_id) REFERENCES dtb_features (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE dtb_features_group_link DROP CONSTRAINT FK_8F851BA433EABFEB');
        $this->addSql('ALTER TABLE dtb_features_group_link DROP CONSTRAINT FK_8F851BA4CEC89005');
        $this->addSql('DROP TABLE dtb_features_group_link');
    }
}
