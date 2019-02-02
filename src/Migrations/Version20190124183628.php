<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190124183628 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('ALTER TABLE stamp_quote ADD shipping_country_id INT NOT NULL');
//        $this->addSql('ALTER TABLE stamp_quote ADD CONSTRAINT FK_4FF0D69141D46E2E FOREIGN KEY (shipping_country_id) REFERENCES country (id)');
//        $this->addSql('CREATE INDEX IDX_4FF0D69141D46E2E ON stamp_quote (shipping_country_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stamp_quote DROP FOREIGN KEY FK_4FF0D69141D46E2E');
        $this->addSql('DROP INDEX IDX_4FF0D69141D46E2E ON stamp_quote');
        $this->addSql('ALTER TABLE stamp_quote DROP shipping_country_id');
    }
}
