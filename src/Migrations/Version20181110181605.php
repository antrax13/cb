<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110181605 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE brand_sketch (id INT AUTO_INCREMENT NOT NULL, stamp_type_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, weight VARCHAR(255) DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, dimension INT DEFAULT NULL, UNIQUE INDEX UNIQ_3A7F22DBEC05B29 (stamp_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stamp_type (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand_sketch ADD CONSTRAINT FK_3A7F22DBEC05B29 FOREIGN KEY (stamp_type_id) REFERENCES stamp_type (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brand_sketch DROP FOREIGN KEY FK_3A7F22DBEC05B29');
        $this->addSql('DROP TABLE brand_sketch');
        $this->addSql('DROP TABLE stamp_type');
    }
}
