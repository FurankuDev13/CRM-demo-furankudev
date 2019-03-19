<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190319135251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company_address ADD company_address_type_id INT NOT NULL, ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE company_address ADD CONSTRAINT FK_2D1C7556D1584176 FOREIGN KEY (company_address_type_id) REFERENCES company_address_type (id)');
        $this->addSql('ALTER TABLE company_address ADD CONSTRAINT FK_2D1C7556979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_2D1C7556D1584176 ON company_address (company_address_type_id)');
        $this->addSql('CREATE INDEX IDX_2D1C7556979B1AD6 ON company_address (company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company_address DROP FOREIGN KEY FK_2D1C7556D1584176');
        $this->addSql('ALTER TABLE company_address DROP FOREIGN KEY FK_2D1C7556979B1AD6');
        $this->addSql('DROP INDEX IDX_2D1C7556D1584176 ON company_address');
        $this->addSql('DROP INDEX IDX_2D1C7556979B1AD6 ON company_address');
        $this->addSql('ALTER TABLE company_address DROP company_address_type_id, DROP company_id');
    }
}
