<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250915084349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carnet ADD permission_id INT NOT NULL');
        $this->addSql('ALTER TABLE carnet ADD CONSTRAINT FK_576D2650FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_576D2650FED90CCA ON carnet (permission_id)');
        $this->addSql('ALTER TABLE comment ADD post_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)');
        $this->addSql('ALTER TABLE invitation ADD carnet_id INT NOT NULL');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2FA207516 FOREIGN KEY (carnet_id) REFERENCES carnet (id)');
        $this->addSql('CREATE INDEX IDX_F11D61A2FA207516 ON invitation (carnet_id)');
        $this->addSql('ALTER TABLE post ADD carnet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFA207516 FOREIGN KEY (carnet_id) REFERENCES carnet (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DFA207516 ON post (carnet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carnet DROP FOREIGN KEY FK_576D2650FED90CCA');
        $this->addSql('DROP INDEX UNIQ_576D2650FED90CCA ON carnet');
        $this->addSql('ALTER TABLE carnet DROP permission_id');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('DROP INDEX IDX_9474526C4B89032C ON comment');
        $this->addSql('ALTER TABLE comment DROP post_id');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A2FA207516');
        $this->addSql('DROP INDEX IDX_F11D61A2FA207516 ON invitation');
        $this->addSql('ALTER TABLE invitation DROP carnet_id');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFA207516');
        $this->addSql('DROP INDEX IDX_5A8A6C8DFA207516 ON post');
        $this->addSql('ALTER TABLE post DROP carnet_id');
    }
}
