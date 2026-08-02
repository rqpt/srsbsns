<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260802124729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set phone_number and email columns as unique.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__contact AS SELECT id, name, surname, phone_number, email FROM contact');
        $this->addSql('DROP TABLE contact');
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO contact (id, name, surname, phone_number, email) SELECT id, name, surname, phone_number, email FROM __temp__contact');
        $this->addSql('DROP TABLE __temp__contact');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E6386B01BC5B ON contact (phone_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638E7927C74 ON contact (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__contact AS SELECT id, name, surname, phone_number, email FROM contact');
        $this->addSql('DROP TABLE contact');
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO contact (id, name, surname, phone_number, email) SELECT id, name, surname, phone_number, email FROM __temp__contact');
        $this->addSql('DROP TABLE __temp__contact');
    }
}
