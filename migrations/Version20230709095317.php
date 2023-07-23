<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230709095317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement ADD nom_id INT NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EC8121CE9 FOREIGN KEY (nom_id) REFERENCES artiste (id)');
        $this->addSql('CREATE INDEX IDX_B26681EC8121CE9 ON evenement (nom_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EC8121CE9');
        $this->addSql('DROP INDEX IDX_B26681EC8121CE9 ON evenement');
        $this->addSql('ALTER TABLE evenement DROP nom_id');
    }
}
