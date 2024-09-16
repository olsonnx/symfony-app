<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908003341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notices (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content VARCHAR(1023) DEFAULT NULL, INDEX IDX_6E2C61D212469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notice_tag (notice_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_295E70307D540AB (notice_id), INDEX IDX_295E7030BAD26311 (tag_id), PRIMARY KEY(notice_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notices ADD CONSTRAINT FK_6E2C61D212469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE notice_tag ADD CONSTRAINT FK_295E70307D540AB FOREIGN KEY (notice_id) REFERENCES notices (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notice_tag ADD CONSTRAINT FK_295E7030BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX uq_categories_title ON categories');
        $this->addSql('ALTER TABLE categories CHANGE title title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notices DROP FOREIGN KEY FK_6E2C61D212469DE2');
        $this->addSql('ALTER TABLE notice_tag DROP FOREIGN KEY FK_295E70307D540AB');
        $this->addSql('ALTER TABLE notice_tag DROP FOREIGN KEY FK_295E7030BAD26311');
        $this->addSql('DROP TABLE notices');
        $this->addSql('DROP TABLE notice_tag');
        $this->addSql('ALTER TABLE categories CHANGE title title VARCHAR(64) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uq_categories_title ON categories (title)');
    }
}
