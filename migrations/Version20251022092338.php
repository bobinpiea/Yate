<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251022092338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris_annonce (favoris_id INT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_43BF3AE051E8871B (favoris_id), INDEX IDX_43BF3AE08805AB2F (annonce_id), PRIMARY KEY(favoris_id, annonce_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris_annonce ADD CONSTRAINT FK_43BF3AE051E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_annonce ADD CONSTRAINT FK_43BF3AE08805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A76ED395');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_BLOG_USER_CASCADE');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514360BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_CATEGORIE_USER_CASCADE');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD63473A201E5 FOREIGN KEY (createur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE echanger DROP FOREIGN KEY FK_ECHANGER_DEST_CASCADE');
        $this->addSql('ALTER TABLE echanger DROP FOREIGN KEY FK_ECHANGER_EXP_CASCADE');
        $this->addSql('ALTER TABLE echanger ADD CONSTRAINT FK_3762266310335F61 FOREIGN KEY (expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE echanger ADD CONSTRAINT FK_37622663A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_MESSAGE_USER_CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_PHOTO_ANNONCE_CASCADE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784188805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B5511460BB6FE6');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B5511460BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_SUJET_USER_CASCADE');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_annonce DROP FOREIGN KEY FK_43BF3AE051E8871B');
        $this->addSql('ALTER TABLE favoris_annonce DROP FOREIGN KEY FK_43BF3AE08805AB2F');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE favoris_annonce');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A76ED395');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514360BB6FE6');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_BLOG_USER_CASCADE FOREIGN KEY (auteur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD63473A201E5');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_CATEGORIE_USER_CASCADE FOREIGN KEY (createur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE echanger DROP FOREIGN KEY FK_3762266310335F61');
        $this->addSql('ALTER TABLE echanger DROP FOREIGN KEY FK_37622663A4F84F6E');
        $this->addSql('ALTER TABLE echanger ADD CONSTRAINT FK_ECHANGER_DEST_CASCADE FOREIGN KEY (destinataire_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE echanger ADD CONSTRAINT FK_ECHANGER_EXP_CASCADE FOREIGN KEY (expediteur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F60BB6FE6');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_MESSAGE_USER_CASCADE FOREIGN KEY (auteur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784188805AB2F');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_PHOTO_ANNONCE_CASCADE FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B5511460BB6FE6');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B5511460BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D60BB6FE6');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_SUJET_USER_CASCADE FOREIGN KEY (auteur_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}
