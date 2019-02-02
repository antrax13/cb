<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181114072805 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE brand_sketch (id INT AUTO_INCREMENT NOT NULL, quote_id INT NOT NULL, stamp_type_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, weight VARCHAR(255) DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, dimension VARCHAR(255) DEFAULT NULL, extension VARCHAR(255) NOT NULL, original_file VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, is_removed TINYINT(1) NOT NULL, note LONGTEXT DEFAULT NULL, INDEX IDX_3A7F22DDB805178 (quote_id), INDEX IDX_3A7F22DBEC05B29 (stamp_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipping (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_option_text (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stamp_type (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_81398E09E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, shipping_id INT DEFAULT NULL, request LONGTEXT NOT NULL, answer LONGTEXT DEFAULT NULL, is_removed TINYINT(1) NOT NULL, shipping_to VARCHAR(255) DEFAULT NULL, generated_at DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_6B71CBF49395C3F3 (customer_id), INDEX IDX_6B71CBF44887F3F8 (shipping_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand_sketch ADD CONSTRAINT FK_3A7F22DDB805178 FOREIGN KEY (quote_id) REFERENCES quote (id)');
        $this->addSql('ALTER TABLE brand_sketch ADD CONSTRAINT FK_3A7F22DBEC05B29 FOREIGN KEY (stamp_type_id) REFERENCES stamp_type (id)');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF49395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF44887F3F8 FOREIGN KEY (shipping_id) REFERENCES shipping (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF44887F3F8');
        $this->addSql('ALTER TABLE brand_sketch DROP FOREIGN KEY FK_3A7F22DBEC05B29');
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF49395C3F3');
        $this->addSql('ALTER TABLE brand_sketch DROP FOREIGN KEY FK_3A7F22DDB805178');
        $this->addSql('DROP TABLE brand_sketch');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE payment_option_text');
        $this->addSql('DROP TABLE stamp_type');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE quote');
    }
}
