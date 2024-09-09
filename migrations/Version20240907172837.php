<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240907172837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notice_tag (notice_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_295E70307D540AB (notice_id), INDEX IDX_295E7030BAD26311 (tag_id), PRIMARY KEY(notice_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notice_tag ADD CONSTRAINT FK_295E70307D540AB FOREIGN KEY (notice_id) REFERENCES notices (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notice_tag ADD CONSTRAINT FK_295E7030BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notice_tag DROP FOREIGN KEY FK_295E70307D540AB');
        $this->addSql('ALTER TABLE notice_tag DROP FOREIGN KEY FK_295E7030BAD26311');
        $this->addSql('DROP TABLE notice_tag');
        $this->addSql('DROP TABLE tags');
    }
}
