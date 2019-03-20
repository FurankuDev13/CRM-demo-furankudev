<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190319210510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADAEA34913 ON product (reference)');
        $this->addSql('ALTER TABLE person CHANGE business_phone business_phone VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_users DROP roles, CHANGE last_connnection last_connnection DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_users ADD roles JSON NOT NULL, CHANGE last_connnection last_connnection DATETIME NOT NULL');
        $this->addSql('ALTER TABLE person CHANGE business_phone business_phone VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_D34A04ADAEA34913 ON product');
    }
}
