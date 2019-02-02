<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190110094730 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE stamp_shape (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand_sketch ADD stamp_shape_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brand_sketch ADD CONSTRAINT FK_3A7F22D7BE9BAFE FOREIGN KEY (stamp_shape_id) REFERENCES stamp_shape (id)');
        $this->addSql('CREATE INDEX IDX_3A7F22D7BE9BAFE ON brand_sketch (stamp_shape_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brand_sketch DROP FOREIGN KEY FK_3A7F22D7BE9BAFE');
        $this->addSql('DROP TABLE stamp_shape');
        $this->addSql('DROP INDEX IDX_3A7F22D7BE9BAFE ON brand_sketch');
        $this->addSql('ALTER TABLE brand_sketch DROP stamp_shape_id');
    }
}
