<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250121093825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `tbl_fauteuildagrement` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, usagetxt VARCHAR(255) DEFAULT NULL, largeur DOUBLE PRECISION DEFAULT NULL, profondeur DOUBLE PRECISION DEFAULT NULL, hauteur DOUBLE PRECISION DEFAULT NULL, recouverture VARCHAR(255) DEFAULT NULL, materiaux VARCHAR(255) DEFAULT NULL, tissu VARCHAR(255) DEFAULT NULL, finition VARCHAR(255) DEFAULT NULL, temps VARCHAR(255) DEFAULT NULL, detail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `tbl_fauteuildagrement`');
    }
}
