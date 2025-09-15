<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250915135330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carnet DROP FOREIGN KEY FK_576D2650FED90CCA');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP INDEX UNIQ_576D2650FED90CCA ON carnet');
        $this->addSql('ALTER TABLE carnet DROP permission_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, admin TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE carnet ADD permission_id INT NOT NULL');
        $this->addSql('ALTER TABLE carnet ADD CONSTRAINT FK_576D2650FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_576D2650FED90CCA ON carnet (permission_id)');
    }
}
