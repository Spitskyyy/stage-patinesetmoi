<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128135107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_abat_jour ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_avant_apres ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_banquette ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_dessus_de_lit ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_fauteuil_d_agrement ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_garniture ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_livre_d_or ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_mises_en_scene ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_objets_de_decoration ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_store ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_tete_de_lit ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
        $this->addSql('ALTER TABLE tbl_voilage_rideaux_doubles ADD pictures JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP picture');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_abat_jour ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_avant_apres ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_banquette ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_dessus_de_lit ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_fauteuil_d_agrement ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_garniture ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_livre_d_or ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_mises_en_scene ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_objets_de_decoration ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_store ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_tete_de_lit ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
        $this->addSql('ALTER TABLE tbl_voilage_rideaux_doubles ADD picture VARCHAR(255) DEFAULT NULL, DROP pictures');
    }
}
