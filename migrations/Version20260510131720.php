<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260510131720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP image_paths');
        $this->addSql('ALTER TABLE etablissement ADD image_filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement DROP image_paths');
        $this->addSql('ALTER TABLE evenement DROP image_paths');
        $this->addSql('ALTER TABLE filiere ADD image_filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE filiere DROP image_paths');
        $this->addSql('ALTER TABLE forum DROP image_paths');
        $this->addSql('ALTER TABLE utilisateur ADD photo_filename VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD image_paths JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement ADD image_paths JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement DROP image_filename');
        $this->addSql('ALTER TABLE evenement ADD image_paths JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE filiere ADD image_paths JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE filiere DROP image_filename');
        $this->addSql('ALTER TABLE forum ADD image_paths JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur DROP photo_filename');
    }
}
