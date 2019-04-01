<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190401141351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(128) NOT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, list_price INT NOT NULL, max_discount_rate INT DEFAULT NULL, is_on_home_page TINYINT(1) NOT NULL, rank INT NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_available TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADAEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CDFC73564584665A (product_id), INDEX IDX_CDFC735612469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, contact_type_id INT NOT NULL, company_id INT NOT NULL, last_connection DATETIME DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4C62E638E7927C74 (email), INDEX IDX_4C62E638217BBB47 (person_id), INDEX IDX_4C62E6385F63AD12 (contact_type_id), INDEX IDX_4C62E638979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_address_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, code VARCHAR(128) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request_detail (id INT AUTO_INCREMENT NOT NULL, request_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, comment_field VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_655FBC68427EB8A5 (request_id), INDEX IDX_655FBC684584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, rank INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(128) NOT NULL, lastname VARCHAR(128) NOT NULL, business_phone VARCHAR(128) DEFAULT NULL, cell_phone VARCHAR(128) DEFAULT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, email VARCHAR(254) NOT NULL, password VARCHAR(254) NOT NULL, last_connnection DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C2502824E7927C74 (email), INDEX IDX_C2502824217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user_role (user_id INT NOT NULL, user_role_id INT NOT NULL, INDEX IDX_2D084B47A76ED395 (user_id), INDEX IDX_2D084B478E0E3CA6 (user_role_id), PRIMARY KEY(user_id, user_role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, discount_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, siren_number VARCHAR(128) DEFAULT NULL, is_customer TINYINT(1) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_4FBF094F4C7C611F (discount_id), INDEX IDX_4FBF094FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, user_id INT DEFAULT NULL, request_id INT DEFAULT NULL, attachment_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_on_board TINYINT(1) NOT NULL, INDEX IDX_9474526C979B1AD6 (company_id), INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C427EB8A5 (request_id), UNIQUE INDEX UNIQ_9474526C464E68B (attachment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, rate INT NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE handling_status (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(128) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company_address (id INT AUTO_INCREMENT NOT NULL, company_address_type_id INT NOT NULL, company_id INT NOT NULL, first_address_field LONGTEXT NOT NULL, second_address_field LONGTEXT DEFAULT NULL, postal_code VARCHAR(128) NOT NULL, city VARCHAR(128) NOT NULL, country VARCHAR(128) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_2D1C7556D1584176 (company_address_type_id), INDEX IDX_2D1C7556979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request (id INT AUTO_INCREMENT NOT NULL, handling_status_id INT NOT NULL, request_type_id INT NOT NULL, contact_id INT NOT NULL, title VARCHAR(128) NOT NULL, body LONGTEXT NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_3B978F9FE0605539 (handling_status_id), INDEX IDX_3B978F9FEF68FEC4 (request_type_id), INDEX IDX_3B978F9FE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6385F63AD12 FOREIGN KEY (contact_type_id) REFERENCES contact_type (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE request_detail ADD CONSTRAINT FK_655FBC68427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id)');
        $this->addSql('ALTER TABLE request_detail ADD CONSTRAINT FK_655FBC684584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE app_users ADD CONSTRAINT FK_C2502824217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE user_user_role ADD CONSTRAINT FK_2D084B47A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user_role ADD CONSTRAINT FK_2D084B478E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F4C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id)');
        $this->addSql('ALTER TABLE company_address ADD CONSTRAINT FK_2D1C7556D1584176 FOREIGN KEY (company_address_type_id) REFERENCES company_address_type (id)');
        $this->addSql('ALTER TABLE company_address ADD CONSTRAINT FK_2D1C7556979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FE0605539 FOREIGN KEY (handling_status_id) REFERENCES handling_status (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FEF68FEC4 FOREIGN KEY (request_type_id) REFERENCES request_type (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE request_detail DROP FOREIGN KEY FK_655FBC684584665A');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FE7A1254A');
        $this->addSql('ALTER TABLE company_address DROP FOREIGN KEY FK_2D1C7556D1584176');
        $this->addSql('ALTER TABLE user_user_role DROP FOREIGN KEY FK_2D084B478E0E3CA6');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C464E68B');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6385F63AD12');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638217BBB47');
        $this->addSql('ALTER TABLE app_users DROP FOREIGN KEY FK_C2502824217BBB47');
        $this->addSql('ALTER TABLE user_user_role DROP FOREIGN KEY FK_2D084B47A76ED395');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638979B1AD6');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C979B1AD6');
        $this->addSql('ALTER TABLE company_address DROP FOREIGN KEY FK_2D1C7556979B1AD6');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F4C7C611F');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FEF68FEC4');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FE0605539');
        $this->addSql('ALTER TABLE request_detail DROP FOREIGN KEY FK_655FBC68427EB8A5');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C427EB8A5');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE company_address_type');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE request_detail');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE contact_type');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE user_user_role');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE request_type');
        $this->addSql('DROP TABLE handling_status');
        $this->addSql('DROP TABLE company_address');
        $this->addSql('DROP TABLE request');
    }
}
