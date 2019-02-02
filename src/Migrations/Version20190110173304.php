<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190110173304 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stamp_quote_sketch ADD handle_color_id INT DEFAULT NULL, ADD handle_shape_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32C4BE4E0F5 FOREIGN KEY (handle_color_id) REFERENCES handle_color (id)');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32C611893FB FOREIGN KEY (handle_shape_id) REFERENCES handle_shape (id)');
        $this->addSql('CREATE INDEX IDX_7F87F32C4BE4E0F5 ON stamp_quote_sketch (handle_color_id)');
        $this->addSql('CREATE INDEX IDX_7F87F32C611893FB ON stamp_quote_sketch (handle_shape_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stamp_quote_sketch DROP FOREIGN KEY FK_7F87F32C4BE4E0F5');
        $this->addSql('ALTER TABLE stamp_quote_sketch DROP FOREIGN KEY FK_7F87F32C611893FB');
        $this->addSql('DROP INDEX IDX_7F87F32C4BE4E0F5 ON stamp_quote_sketch');
        $this->addSql('DROP INDEX IDX_7F87F32C611893FB ON stamp_quote_sketch');
        $this->addSql('ALTER TABLE stamp_quote_sketch DROP handle_color_id, DROP handle_shape_id');
    }
}
