<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250916210148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, nature_bien_id INT DEFAULT NULL, transaction_id INT DEFAULT NULL, user_id INT DEFAULT NULL, reference VARCHAR(30) NOT NULL, titre VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, ville VARCHAR(100) NOT NULL, commune VARCHAR(100) NOT NULL, prix INT NOT NULL, date_publication VARCHAR(255) NOT NULL, statut_annonce VARCHAR(20) DEFAULT NULL, superficie INT DEFAULT NULL, nb_pieces INT DEFAULT NULL, nb_chambres INT DEFAULT NULL, annee_construction VARCHAR(255) DEFAULT NULL, salle_bain TINYINT(1) DEFAULT NULL, balcon TINYINT(1) DEFAULT NULL, garage TINYINT(1) DEFAULT NULL, jardin TINYINT(1) DEFAULT NULL, piscine TINYINT(1) DEFAULT NULL, climatisateur TINYINT(1) DEFAULT NULL, ascenseur TINYINT(1) DEFAULT NULL, terrasse TINYINT(1) DEFAULT NULL, cuisine TINYINT(1) DEFAULT NULL, wc TINYINT(1) DEFAULT NULL, salon TINYINT(1) DEFAULT NULL, etage INT DEFAULT NULL, surface_habitable INT DEFAULT NULL, constructible TINYINT(1) DEFAULT NULL, viabilise TINYINT(1) DEFAULT NULL, nb_colocataires INT DEFAULT NULL, apatam TINYINT(1) DEFAULT NULL, duree_contrat INT DEFAULT NULL, date_debut VARCHAR(255) DEFAULT NULL, date_fin VARCHAR(255) DEFAULT NULL, charge INT DEFAULT NULL, INDEX IDX_F65593E5A516F3DA (nature_bien_id), INDEX IDX_F65593E52FC0CB0F (transaction_id), INDEX IDX_F65593E5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, titre VARCHAR(150) NOT NULL, article LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, url VARCHAR(255) DEFAULT NULL, categorie VARCHAR(50) NOT NULL, INDEX IDX_C015514360BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, createur_id INT DEFAULT NULL, nom VARCHAR(75) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_497DD63473A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, annonce_id INT DEFAULT NULL, type VARCHAR(100) DEFAULT NULL, date_depot DATETIME DEFAULT NULL, INDEX IDX_D8698A768805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE echanger (id INT AUTO_INCREMENT NOT NULL, expediteur_id INT DEFAULT NULL, destinataire_id INT DEFAULT NULL, annonce_id INT DEFAULT NULL, message LONGTEXT NOT NULL, date_envoi DATETIME NOT NULL, INDEX IDX_3762266310335F61 (expediteur_id), INDEX IDX_37622663A4F84F6E (destinataire_id), INDEX IDX_376226638805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sujet_id INT NOT NULL, auteur_id INT DEFAULT NULL, date_message DATETIME NOT NULL, texte LONGTEXT NOT NULL, INDEX IDX_B6BD307F7C4D497E (sujet_id), INDEX IDX_B6BD307F60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nature_bien (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, annonce_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, nom VARCHAR(30) DEFAULT NULL, INDEX IDX_14B784188805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signalement (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, annonce_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, commentaire LONGTEXT DEFAULT NULL, date_signalement DATETIME NOT NULL, INDEX IDX_F4B5511460BB6FE6 (auteur_id), INDEX IDX_F4B551148805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sujet (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, auteur_id INT DEFAULT NULL, titre VARCHAR(100) NOT NULL, date_creation DATETIME NOT NULL, bloque TINYINT(1) NOT NULL, INDEX IDX_2E13599DBCF5E72D (categorie_id), INDEX IDX_2E13599D60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, type_transaction VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_annonce (user_id INT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_AE588DEFA76ED395 (user_id), INDEX IDX_AE588DEF8805AB2F (annonce_id), PRIMARY KEY(user_id, annonce_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_annonce_favorite (user_id INT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_5898C2C6A76ED395 (user_id), INDEX IDX_5898C2C68805AB2F (annonce_id), PRIMARY KEY(user_id, annonce_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A516F3DA FOREIGN KEY (nature_bien_id) REFERENCES nature_bien (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E52FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514360BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD63473A201E5 FOREIGN KEY (createur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A768805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE echanger ADD CONSTRAINT FK_3762266310335F61 FOREIGN KEY (expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE echanger ADD CONSTRAINT FK_37622663A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE echanger ADD CONSTRAINT FK_376226638805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujet (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784188805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B5511460BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B551148805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_annonce ADD CONSTRAINT FK_AE588DEFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_annonce ADD CONSTRAINT FK_AE588DEF8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_annonce_favorite ADD CONSTRAINT FK_5898C2C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_annonce_favorite ADD CONSTRAINT FK_5898C2C68805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A516F3DA');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E52FC0CB0F');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A76ED395');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514360BB6FE6');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD63473A201E5');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A768805AB2F');
        $this->addSql('ALTER TABLE echanger DROP FOREIGN KEY FK_3762266310335F61');
        $this->addSql('ALTER TABLE echanger DROP FOREIGN KEY FK_37622663A4F84F6E');
        $this->addSql('ALTER TABLE echanger DROP FOREIGN KEY FK_376226638805AB2F');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F7C4D497E');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F60BB6FE6');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784188805AB2F');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B5511460BB6FE6');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B551148805AB2F');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599DBCF5E72D');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D60BB6FE6');
        $this->addSql('ALTER TABLE user_annonce DROP FOREIGN KEY FK_AE588DEFA76ED395');
        $this->addSql('ALTER TABLE user_annonce DROP FOREIGN KEY FK_AE588DEF8805AB2F');
        $this->addSql('ALTER TABLE user_annonce_favorite DROP FOREIGN KEY FK_5898C2C6A76ED395');
        $this->addSql('ALTER TABLE user_annonce_favorite DROP FOREIGN KEY FK_5898C2C68805AB2F');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE echanger');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE nature_bien');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE signalement');
        $this->addSql('DROP TABLE sujet');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_annonce');
        $this->addSql('DROP TABLE user_annonce_favorite');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
