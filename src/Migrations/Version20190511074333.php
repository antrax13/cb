<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190511074333 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sketch_reference (id INT AUTO_INCREMENT NOT NULL, brand_sketch_id INT NOT NULL, file VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, original_file VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4182110838ECA5F4 (brand_sketch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sketch_reference ADD CONSTRAINT FK_4182110838ECA5F4 FOREIGN KEY (brand_sketch_id) REFERENCES brand_sketch (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sketch_reference');
    }
}
