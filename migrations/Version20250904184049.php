<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250904184049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, titre VARCHAR(150) NOT NULL, article LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, url VARCHAR(255) DEFAULT NULL, categorie VARCHAR(50) NOT NULL, INDEX IDX_C015514360BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514360BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(100) DEFAULT NULL, ADD prenom VARCHAR(100) DEFAULT NULL, ADD avatar VARCHAR(255) DEFAULT NULL, ADD pseudo VARCHAR(50) DEFAULT NULL, CHANGE email email VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514360BB6FE6');
        $this->addSql('DROP TABLE blog');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP avatar, DROP pseudo, CHANGE email email VARCHAR(180) NOT NULL');
    }
}
