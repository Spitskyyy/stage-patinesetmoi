<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123141030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE objets_de_decoration (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_abat_jour (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, materials LONGTEXT DEFAULT NULL, choice_of_strucure LONGTEXT DEFAULT NULL, lampshade_finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_avant_apres (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_banquette (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, usetxt LONGTEXT DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, covering_or_complete_repair LONGTEXT DEFAULT NULL, materials LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_dessus_de_lit (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, usetxt LONGTEXT DEFAULT NULL, length DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, lining LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, bedspread_finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_fauteuil_d_agrement (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, usetxt LONGTEXT DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, covering_or_complete_repair LONGTEXT DEFAULT NULL, materials LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_garniture (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_livre_d_or (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_mises_en_scene (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_store (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, usetxt LONGTEXT DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, lining LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_tringlerie (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, detail LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `tbl_user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_voilage_rideaux_doubles (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, usetxt LONGTEXT DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, lining LONGTEXT DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, curtain_head_finishing LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tete_de_lit (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, fabric LONGTEXT DEFAULT NULL, materials LONGTEXT DEFAULT NULL, support LONGTEXT DEFAULT NULL, headboard_finishes LONGTEXT DEFAULT NULL, time LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE objets_de_decoration');
        $this->addSql('DROP TABLE tbl_abat_jour');
        $this->addSql('DROP TABLE tbl_avant_apres');
        $this->addSql('DROP TABLE tbl_banquette');
        $this->addSql('DROP TABLE tbl_dessus_de_lit');
        $this->addSql('DROP TABLE tbl_fauteuil_d_agrement');
        $this->addSql('DROP TABLE tbl_garniture');
        $this->addSql('DROP TABLE tbl_livre_d_or');
        $this->addSql('DROP TABLE tbl_mises_en_scene');
        $this->addSql('DROP TABLE tbl_store');
        $this->addSql('DROP TABLE tbl_tringlerie');
        $this->addSql('DROP TABLE `tbl_user`');
        $this->addSql('DROP TABLE tbl_voilage_rideaux_doubles');
        $this->addSql('DROP TABLE tete_de_lit');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
