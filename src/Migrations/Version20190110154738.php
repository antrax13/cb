<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190110154738 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE stamp_quote_sketch (id INT AUTO_INCREMENT NOT NULL, stamp_quote_id INT NOT NULL, stamp_type_id INT DEFAULT NULL, stamp_shape_id INT DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, original_file VARCHAR(255) DEFAULT NULL, extension VARCHAR(255) DEFAULT NULL, size VARCHAR(255) DEFAULT NULL, is_sphere TINYINT(1) DEFAULT NULL, sphere_diameter INT DEFAULT NULL, size_side_a INT DEFAULT NULL, size_side_b INT DEFAULT NULL, size_diameter INT DEFAULT NULL, size_custom_note LONGTEXT NOT NULL, size_note LONGTEXT DEFAULT NULL, handle VARCHAR(255) DEFAULT NULL, qty INT NOT NULL, INDEX IDX_7F87F32CF04F873D (stamp_quote_id), INDEX IDX_7F87F32CBEC05B29 (stamp_type_id), INDEX IDX_7F87F32C7BE9BAFE (stamp_shape_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stamp_quote (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, shipping_country VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32CF04F873D FOREIGN KEY (stamp_quote_id) REFERENCES stamp_quote (id)');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32CBEC05B29 FOREIGN KEY (stamp_type_id) REFERENCES stamp_type (id)');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32C7BE9BAFE FOREIGN KEY (stamp_shape_id) REFERENCES stamp_shape (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stamp_quote_sketch DROP FOREIGN KEY FK_7F87F32CF04F873D');
        $this->addSql('DROP TABLE stamp_quote_sketch');
        $this->addSql('DROP TABLE stamp_quote');
    }
}
