<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190123070724 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stamp_quote_sketch CHANGE sphere_diameter sphere_diameter VARCHAR(255) DEFAULT NULL, CHANGE size_side_a size_side_a VARCHAR(255) DEFAULT NULL, CHANGE size_side_b size_side_b VARCHAR(255) DEFAULT NULL, CHANGE size_diameter size_diameter VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE stamp_quote_sketch CHANGE sphere_diameter sphere_diameter INT DEFAULT NULL, CHANGE size_side_a size_side_a INT DEFAULT NULL, CHANGE size_side_b size_side_b INT DEFAULT NULL, CHANGE size_diameter size_diameter INT DEFAULT NULL');
    }
}
