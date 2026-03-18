<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318043208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE plg_coupon ADD target_users JSON DEFAULT NULL");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE plg_coupon DROP target_users");
    }
}
