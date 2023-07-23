<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230709165846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EC8121CE9');
        $this->addSql('ALTER TABLE evenement ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EC8121CE9 FOREIGN KEY (nom_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EC8121CE9');
        $this->addSql('ALTER TABLE evenement DROP image_name');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EC8121CE9 FOREIGN KEY (nom_id) REFERENCES artiste (id)');
    }
}
