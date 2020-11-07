<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201105130625 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Allow judging.starttime to be unset.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE judging CHANGE judgehost judgehost VARCHAR(64) DEFAULT NULL COMMENT \'Judgehost that performed the judging\', CHANGE starttime starttime NUMERIC(32, 9) UNSIGNED DEFAULT NULL COMMENT \'Time judging started\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE judging CHANGE judgehost judgehost VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci` COMMENT \'Judgehost that performed the judging\', CHANGE starttime starttime NUMERIC(32, 9) UNSIGNED NOT NULL COMMENT \'Time judging started\'');
    }
}