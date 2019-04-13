<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412130720 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_reference (id INT AUTO_INCREMENT NOT NULL, custom_product_id INT DEFAULT NULL, shop_product_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, file_size VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, position INT NOT NULL, INDEX IDX_C003FF9E7A7B5AF0 (custom_product_id), INDEX IDX_C003FF9E3FF78B7C (shop_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_reference ADD CONSTRAINT FK_C003FF9E7A7B5AF0 FOREIGN KEY (custom_product_id) REFERENCES custom_product_info (id)');
        $this->addSql('ALTER TABLE product_reference ADD CONSTRAINT FK_C003FF9E3FF78B7C FOREIGN KEY (shop_product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_reference');
    }
}
