<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181110182536 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brand_sketch ADD quote_id INT NOT NULL');
        $this->addSql('ALTER TABLE brand_sketch ADD CONSTRAINT FK_3A7F22DDB805178 FOREIGN KEY (quote_id) REFERENCES quote (id)');
        $this->addSql('CREATE INDEX IDX_3A7F22DDB805178 ON brand_sketch (quote_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brand_sketch DROP FOREIGN KEY FK_3A7F22DDB805178');
        $this->addSql('DROP INDEX IDX_3A7F22DDB805178 ON brand_sketch');
        $this->addSql('ALTER TABLE brand_sketch DROP quote_id');
    }
}
