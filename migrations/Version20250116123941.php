<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116123941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_banquette ADD finition VARCHAR(255) DEFAULT NULL, ADD tissu VARCHAR(255) DEFAULT NULL, ADD usagetxt VARCHAR(255) DEFAULT NULL, ADD materiaux VARCHAR(255) DEFAULT NULL, ADD temp VARCHAR(255) DEFAULT NULL, ADD recouverture VARCHAR(255) DEFAULT NULL, ADD largeur DOUBLE PRECISION DEFAULT NULL, ADD profondeur DOUBLE PRECISION DEFAULT NULL, ADD hauteur DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `tbl_banquette` DROP finition, DROP tissu, DROP usagetxt, DROP materiaux, DROP temp, DROP recouverture, DROP largeur, DROP profondeur, DROP hauteur');
    }
}
