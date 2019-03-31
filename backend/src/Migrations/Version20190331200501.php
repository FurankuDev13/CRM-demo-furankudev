<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190331200501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment ADD request_id INT DEFAULT NULL, ADD attachment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id)');
        $this->addSql('CREATE INDEX IDX_9474526C427EB8A5 ON comment (request_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9474526C464E68B ON comment (attachment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C427EB8A5');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C464E68B');
        $this->addSql('DROP INDEX IDX_9474526C427EB8A5 ON comment');
        $this->addSql('DROP INDEX UNIQ_9474526C464E68B ON comment');
        $this->addSql('ALTER TABLE comment DROP request_id, DROP attachment_id');
    }
}
