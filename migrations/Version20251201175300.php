<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251201175300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carnet (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, titre VARCHAR(100) NOT NULL, date_carnet DATE NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_576D2650FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, utilisateur_id INT NOT NULL, parent_id INT DEFAULT NULL, date_comment DATETIME NOT NULL, texte LONGTEXT NOT NULL, INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CFB88E14F (utilisateur_id), INDEX IDX_9474526C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, carnet_id INT NOT NULL, utilisateur_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(100) NOT NULL, date_invite DATE NOT NULL, INDEX IDX_F11D61A2FA207516 (carnet_id), INDEX IDX_F11D61A2FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, image_file VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_14B784184B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, carnet_id INT NOT NULL, titre VARCHAR(255) NOT NULL, date_post DATETIME NOT NULL, texte LONGTEXT DEFAULT NULL, latitude NUMERIC(10, 7) DEFAULT NULL, longitude NUMERIC(10, 7) DEFAULT NULL, INDEX IDX_5A8A6C8DFA207516 (carnet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_carnet (utilisateur_id INT NOT NULL, carnet_id INT NOT NULL, INDEX IDX_36C01CDBFB88E14F (utilisateur_id), INDEX IDX_36C01CDBFA207516 (carnet_id), PRIMARY KEY(utilisateur_id, carnet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_post (utilisateur_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_930B00B7FB88E14F (utilisateur_id), INDEX IDX_930B00B74B89032C (post_id), PRIMARY KEY(utilisateur_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_comment (utilisateur_id INT NOT NULL, comment_id INT NOT NULL, INDEX IDX_EE7FA5FEFB88E14F (utilisateur_id), INDEX IDX_EE7FA5FEF8697D13 (comment_id), PRIMARY KEY(utilisateur_id, comment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carnet ADD CONSTRAINT FK_576D2650FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2FA207516 FOREIGN KEY (carnet_id) REFERENCES carnet (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784184B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFA207516 FOREIGN KEY (carnet_id) REFERENCES carnet (id)');
        $this->addSql('ALTER TABLE utilisateur_carnet ADD CONSTRAINT FK_36C01CDBFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_carnet ADD CONSTRAINT FK_36C01CDBFA207516 FOREIGN KEY (carnet_id) REFERENCES carnet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_post ADD CONSTRAINT FK_930B00B7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_post ADD CONSTRAINT FK_930B00B74B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_comment ADD CONSTRAINT FK_EE7FA5FEFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_comment ADD CONSTRAINT FK_EE7FA5FEF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carnet DROP FOREIGN KEY FK_576D2650FB88E14F');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CFB88E14F');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C727ACA70');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A2FA207516');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A2FB88E14F');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784184B89032C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFA207516');
        $this->addSql('ALTER TABLE utilisateur_carnet DROP FOREIGN KEY FK_36C01CDBFB88E14F');
        $this->addSql('ALTER TABLE utilisateur_carnet DROP FOREIGN KEY FK_36C01CDBFA207516');
        $this->addSql('ALTER TABLE utilisateur_post DROP FOREIGN KEY FK_930B00B7FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_post DROP FOREIGN KEY FK_930B00B74B89032C');
        $this->addSql('ALTER TABLE utilisateur_comment DROP FOREIGN KEY FK_EE7FA5FEFB88E14F');
        $this->addSql('ALTER TABLE utilisateur_comment DROP FOREIGN KEY FK_EE7FA5FEF8697D13');
        $this->addSql('DROP TABLE carnet');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE utilisateur_carnet');
        $this->addSql('DROP TABLE utilisateur_post');
        $this->addSql('DROP TABLE utilisateur_comment');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
