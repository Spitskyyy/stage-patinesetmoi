<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123104458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abat_jour (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, materials LONGTEXT DEFAULT NULL, choice_of_strucure LONGTEXT DEFAULT NULL, lampshade_finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banquette (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, usetxt LONGTEXT DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, covering_or_complete_repair LONGTEXT DEFAULT NULL, materials LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dessus_de_lit (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, usetxt LONGTEXT DEFAULT NULL, length DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, lining LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, bedspread_finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre_dor (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mises_en_scene (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objets_de_decoration (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, usagetxt LONGTEXT DEFAULT NULL, largeur DOUBLE PRECISION DEFAULT NULL, hauteur DOUBLE PRECISION DEFAULT NULL, doublure LONGTEXT DEFAULT NULL, tissu LONGTEXT DEFAULT NULL, temps DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stores (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, lining LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tete_de_lit (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, materials LONGTEXT DEFAULT NULL, support LONGTEXT DEFAULT NULL, headboard_finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE tbl_avantapres');
        $this->addSql('DROP TABLE tbl_banquette');
        $this->addSql('DROP TABLE tbl_fauteuildagrement');
        $this->addSql('DROP TABLE tbl_garniture');
        $this->addSql('DROP TABLE tringlerie');
        $this->addSql('DROP TABLE voilage_rideaux_doubles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbl_avantapres (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, detail LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tbl_banquette (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, finition VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, tissu VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, usagetxt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, materiaux VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, temp VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, recouverture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, largeur DOUBLE PRECISION DEFAULT NULL, profondeur DOUBLE PRECISION DEFAULT NULL, hauteur DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tbl_fauteuildagrement (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, usagetxt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, largeur DOUBLE PRECISION DEFAULT NULL, profondeur DOUBLE PRECISION DEFAULT NULL, hauteur DOUBLE PRECISION DEFAULT NULL, recouverture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, materiaux VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, tissu VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, finition VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, temps VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, detail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tbl_garniture (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, detail LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tringlerie (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, detail LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE voilage_rideaux_doubles (id INT AUTO_INCREMENT NOT NULL, usagetxt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, largeur DOUBLE PRECISION DEFAULT NULL, hauteur DOUBLE PRECISION DEFAULT NULL, doublure VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, tissu VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, finition VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, temps VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE abat_jour');
        $this->addSql('DROP TABLE banquette');
        $this->addSql('DROP TABLE dessus_de_lit');
        $this->addSql('DROP TABLE livre_dor');
        $this->addSql('DROP TABLE mises_en_scene');
        $this->addSql('DROP TABLE objets_de_decoration');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE stores');
        $this->addSql('DROP TABLE tete_de_lit');
    }
}
