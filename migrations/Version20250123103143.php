<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123103143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abat_jour (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, materials LONGTEXT DEFAULT NULL, choice_of_strucure LONGTEXT DEFAULT NULL, lampshade_finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dessus_de_lit (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, usetxt LONGTEXT DEFAULT NULL, length DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, lining LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, bedspread_finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre_dor (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mises_en_scene (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objets_de_decoration (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, usagetxt LONGTEXT DEFAULT NULL, largeur DOUBLE PRECISION DEFAULT NULL, hauteur DOUBLE PRECISION DEFAULT NULL, doublure LONGTEXT DEFAULT NULL, tissu LONGTEXT DEFAULT NULL, temps DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stores (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, lining LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `tbl_user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tete_de_lit (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, materials LONGTEXT DEFAULT NULL, support LONGTEXT DEFAULT NULL, headboard_finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE abat_jour');
        $this->addSql('DROP TABLE dessus_de_lit');
        $this->addSql('DROP TABLE livre_dor');
        $this->addSql('DROP TABLE mises_en_scene');
        $this->addSql('DROP TABLE objets_de_decoration');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE stores');
        $this->addSql('DROP TABLE `tbl_user`');
        $this->addSql('DROP TABLE tete_de_lit');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
