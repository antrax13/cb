<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190422073629 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE branding_iron_size (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand_sketch (id INT AUTO_INCREMENT NOT NULL, quote_id INT NOT NULL, stamp_type_id INT DEFAULT NULL, stamp_shape_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, weight NUMERIC(10, 2) DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, dimension VARCHAR(255) DEFAULT NULL, extension VARCHAR(255) NOT NULL, original_file VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, is_removed TINYINT(1) NOT NULL, note LONGTEXT DEFAULT NULL, handle VARCHAR(255) DEFAULT NULL, qty INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_3A7F22DDB805178 (quote_id), INDEX IDX_3A7F22DBEC05B29 (stamp_type_id), INDEX IDX_3A7F22D7BE9BAFE (stamp_shape_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, fedex_code VARCHAR(5) DEFAULT NULL, fedex_delivery_day INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_81398E09E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE custom_product_info (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, intro LONGTEXT NOT NULL, details LONGTEXT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, fetch_order INT NOT NULL, slug VARCHAR(255) NOT NULL, created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, is_featured TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faq_category (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faq_question (id INT AUTO_INCREMENT NOT NULL, faq_category_id INT NOT NULL, question LONGTEXT NOT NULL, answer LONGTEXT NOT NULL, INDEX IDX_4A55B059F689B0DB (faq_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fedex_delivery (id INT AUTO_INCREMENT NOT NULL, code_a INT NOT NULL, code_b INT NOT NULL, code_c INT NOT NULL, code_d INT NOT NULL, code_e INT NOT NULL, code_eu1 INT NOT NULL, code_eu2 INT NOT NULL, code_eu3 INT NOT NULL, weight NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_photo (id INT AUTO_INCREMENT NOT NULL, file VARCHAR(255) NOT NULL, is_removed TINYINT(1) NOT NULL, title VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE handle_color (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE handle_shape (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, quote_id INT DEFAULT NULL, reference VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, vat NUMERIC(4, 2) NOT NULL, generated_at DATETIME NOT NULL, billing_address LONGTEXT DEFAULT NULL, billing_address_first VARCHAR(255) DEFAULT NULL, invoiced_vat VARCHAR(255) DEFAULT NULL, invoiced_phone VARCHAR(255) DEFAULT NULL, shipping_address LONGTEXT DEFAULT NULL, shipping_phone VARCHAR(255) DEFAULT NULL, is_recalculation_required TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_90651744DB805178 (quote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_item (id INT AUTO_INCREMENT NOT NULL, invoice_id INT NOT NULL, name LONGTEXT NOT NULL, price NUMERIC(10, 2) NOT NULL, qty INT NOT NULL, weight_per_item NUMERIC(10, 2) DEFAULT NULL, is_paypal_item TINYINT(1) NOT NULL, is_shipping_item TINYINT(1) NOT NULL, INDEX IDX_1DDE477B2989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manufacturing_text (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_option_text (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(8, 2) NOT NULL, image VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, intro LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D34A04AD989D9B62 (slug), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_reference (id INT AUTO_INCREMENT NOT NULL, custom_product_id INT DEFAULT NULL, shop_product_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, file_size VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, position INT NOT NULL, INDEX IDX_C003FF9E7A7B5AF0 (custom_product_id), INDEX IDX_C003FF9E3FF78B7C (shop_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, shipping_country_id INT DEFAULT NULL, request LONGTEXT NOT NULL, answer LONGTEXT DEFAULT NULL, is_removed TINYINT(1) NOT NULL, generated_at DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, deadline_date DATETIME DEFAULT NULL, billing_details LONGTEXT DEFAULT NULL, shipping_details LONGTEXT DEFAULT NULL, INDEX IDX_6B71CBF49395C3F3 (customer_id), INDEX IDX_6B71CBF441D46E2E (shipping_country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stamp_quote (id INT AUTO_INCREMENT NOT NULL, shipping_country_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, additional_comment LONGTEXT DEFAULT NULL, INDEX IDX_4FF0D69141D46E2E (shipping_country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stamp_quote_sketch (id INT AUTO_INCREMENT NOT NULL, stamp_quote_id INT NOT NULL, stamp_type_id INT DEFAULT NULL, stamp_shape_id INT DEFAULT NULL, handle_color_id INT DEFAULT NULL, handle_shape_id INT DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, original_file VARCHAR(255) DEFAULT NULL, extension VARCHAR(255) DEFAULT NULL, file_size VARCHAR(255) DEFAULT NULL, is_sphere TINYINT(1) DEFAULT NULL, sphere_diameter VARCHAR(255) DEFAULT NULL, size_side_a VARCHAR(255) DEFAULT NULL, size_side_b VARCHAR(255) DEFAULT NULL, size_diameter VARCHAR(255) DEFAULT NULL, size_custom_note LONGTEXT DEFAULT NULL, size_note LONGTEXT DEFAULT NULL, qty INT NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_7F87F32CF04F873D (stamp_quote_id), INDEX IDX_7F87F32CBEC05B29 (stamp_type_id), INDEX IDX_7F87F32C7BE9BAFE (stamp_shape_id), INDEX IDX_7F87F32C4BE4E0F5 (handle_color_id), INDEX IDX_7F87F32C611893FB (handle_shape_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stamp_shape (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stamp_type (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand_sketch ADD CONSTRAINT FK_3A7F22DDB805178 FOREIGN KEY (quote_id) REFERENCES quote (id)');
        $this->addSql('ALTER TABLE brand_sketch ADD CONSTRAINT FK_3A7F22DBEC05B29 FOREIGN KEY (stamp_type_id) REFERENCES stamp_type (id)');
        $this->addSql('ALTER TABLE brand_sketch ADD CONSTRAINT FK_3A7F22D7BE9BAFE FOREIGN KEY (stamp_shape_id) REFERENCES stamp_shape (id)');
        $this->addSql('ALTER TABLE faq_question ADD CONSTRAINT FK_4A55B059F689B0DB FOREIGN KEY (faq_category_id) REFERENCES faq_category (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id)');
        $this->addSql('ALTER TABLE invoice_item ADD CONSTRAINT FK_1DDE477B2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product_reference ADD CONSTRAINT FK_C003FF9E7A7B5AF0 FOREIGN KEY (custom_product_id) REFERENCES custom_product_info (id)');
        $this->addSql('ALTER TABLE product_reference ADD CONSTRAINT FK_C003FF9E3FF78B7C FOREIGN KEY (shop_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF49395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF441D46E2E FOREIGN KEY (shipping_country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE stamp_quote ADD CONSTRAINT FK_4FF0D69141D46E2E FOREIGN KEY (shipping_country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32CF04F873D FOREIGN KEY (stamp_quote_id) REFERENCES stamp_quote (id)');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32CBEC05B29 FOREIGN KEY (stamp_type_id) REFERENCES stamp_type (id)');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32C7BE9BAFE FOREIGN KEY (stamp_shape_id) REFERENCES stamp_shape (id)');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32C4BE4E0F5 FOREIGN KEY (handle_color_id) REFERENCES handle_color (id)');
        $this->addSql('ALTER TABLE stamp_quote_sketch ADD CONSTRAINT FK_7F87F32C611893FB FOREIGN KEY (handle_shape_id) REFERENCES handle_shape (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF441D46E2E');
        $this->addSql('ALTER TABLE stamp_quote DROP FOREIGN KEY FK_4FF0D69141D46E2E');
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF49395C3F3');
        $this->addSql('ALTER TABLE product_reference DROP FOREIGN KEY FK_C003FF9E7A7B5AF0');
        $this->addSql('ALTER TABLE faq_question DROP FOREIGN KEY FK_4A55B059F689B0DB');
        $this->addSql('ALTER TABLE stamp_quote_sketch DROP FOREIGN KEY FK_7F87F32C4BE4E0F5');
        $this->addSql('ALTER TABLE stamp_quote_sketch DROP FOREIGN KEY FK_7F87F32C611893FB');
        $this->addSql('ALTER TABLE invoice_item DROP FOREIGN KEY FK_1DDE477B2989F1FD');
        $this->addSql('ALTER TABLE product_reference DROP FOREIGN KEY FK_C003FF9E3FF78B7C');
        $this->addSql('ALTER TABLE brand_sketch DROP FOREIGN KEY FK_3A7F22DDB805178');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744DB805178');
        $this->addSql('ALTER TABLE stamp_quote_sketch DROP FOREIGN KEY FK_7F87F32CF04F873D');
        $this->addSql('ALTER TABLE brand_sketch DROP FOREIGN KEY FK_3A7F22D7BE9BAFE');
        $this->addSql('ALTER TABLE stamp_quote_sketch DROP FOREIGN KEY FK_7F87F32C7BE9BAFE');
        $this->addSql('ALTER TABLE brand_sketch DROP FOREIGN KEY FK_3A7F22DBEC05B29');
        $this->addSql('ALTER TABLE stamp_quote_sketch DROP FOREIGN KEY FK_7F87F32CBEC05B29');
        $this->addSql('DROP TABLE branding_iron_size');
        $this->addSql('DROP TABLE brand_sketch');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE custom_product_info');
        $this->addSql('DROP TABLE faq_category');
        $this->addSql('DROP TABLE faq_question');
        $this->addSql('DROP TABLE fedex_delivery');
        $this->addSql('DROP TABLE gallery_photo');
        $this->addSql('DROP TABLE handle_color');
        $this->addSql('DROP TABLE handle_shape');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_item');
        $this->addSql('DROP TABLE manufacturing_text');
        $this->addSql('DROP TABLE payment_option_text');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_reference');
        $this->addSql('DROP TABLE quote');
        $this->addSql('DROP TABLE stamp_quote');
        $this->addSql('DROP TABLE stamp_quote_sketch');
        $this->addSql('DROP TABLE stamp_shape');
        $this->addSql('DROP TABLE stamp_type');
        $this->addSql('DROP TABLE user');
    }
}
