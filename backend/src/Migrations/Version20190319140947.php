<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190319140947 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE request ADD handling_status_id INT NOT NULL, ADD request_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FE0605539 FOREIGN KEY (handling_status_id) REFERENCES handling_status (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FEF68FEC4 FOREIGN KEY (request_type_id) REFERENCES request_type (id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FE0605539 ON request (handling_status_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FEF68FEC4 ON request (request_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FE0605539');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FEF68FEC4');
        $this->addSql('DROP INDEX IDX_3B978F9FE0605539 ON request');
        $this->addSql('DROP INDEX IDX_3B978F9FEF68FEC4 ON request');
        $this->addSql('ALTER TABLE request DROP handling_status_id, DROP request_type_id');
    }
}
