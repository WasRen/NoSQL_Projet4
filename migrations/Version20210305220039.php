<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210305220039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie ADD movietitle VARCHAR(256) NOT NULL, ADD price INT NOT NULL, ADD director VARCHAR(256) NOT NULL, ADD langage VARCHAR(256) NOT NULL, ADD genre VARCHAR(256) NOT NULL, ADD rottem_tomatoes INT NOT NULL, ADD release_date DATETIME NOT NULL, ADD duration INT NOT NULL, ADD distribution_type VARCHAR(256) NOT NULL, ADD production_company VARCHAR(256) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE panier');
        $this->addSql('ALTER TABLE movie DROP movietitle, DROP price, DROP director, DROP langage, DROP genre, DROP rottem_tomatoes, DROP release_date, DROP duration, DROP distribution_type, DROP production_company');
    }
}
