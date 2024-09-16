<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908231741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notices_tags (notice_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_881FA1CF7D540AB (notice_id), INDEX IDX_881FA1CFBAD26311 (tag_id), PRIMARY KEY(notice_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notices_tags ADD CONSTRAINT FK_881FA1CF7D540AB FOREIGN KEY (notice_id) REFERENCES notices (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notices_tags ADD CONSTRAINT FK_881FA1CFBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notices CHANGE status status VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notices_tags DROP FOREIGN KEY FK_881FA1CF7D540AB');
        $this->addSql('ALTER TABLE notices_tags DROP FOREIGN KEY FK_881FA1CFBAD26311');
        $this->addSql('DROP TABLE notices_tags');
        $this->addSql('ALTER TABLE notices CHANGE status status VARCHAR(255) NOT NULL');
    }
}
