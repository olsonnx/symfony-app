<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240908215731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notices ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notices ADD CONSTRAINT FK_6E2C61D2F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_6E2C61D2F675F31B ON notices (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notices DROP FOREIGN KEY FK_6E2C61D2F675F31B');
        $this->addSql('DROP INDEX IDX_6E2C61D2F675F31B ON notices');
        $this->addSql('ALTER TABLE notices DROP author_id');
    }
}
