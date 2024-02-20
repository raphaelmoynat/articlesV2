<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220102555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE nem ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE nem ADD CONSTRAINT FK_9681BD3F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9681BD3F12469DE2 ON nem (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE nem DROP CONSTRAINT FK_9681BD3F12469DE2');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_9681BD3F12469DE2');
        $this->addSql('ALTER TABLE nem DROP category_id');
    }
}
