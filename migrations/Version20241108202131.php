<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108202131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP CONSTRAINT fk_d8698a7657cf1f90');
        $this->addSql('DROP INDEX idx_d8698a7657cf1f90');
        $this->addSql('ALTER TABLE document ADD student_number VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE document DROP student_number_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE document ADD student_number_id INT NOT NULL');
        $this->addSql('ALTER TABLE document DROP student_number');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT fk_d8698a7657cf1f90 FOREIGN KEY (student_number_id) REFERENCES student (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d8698a7657cf1f90 ON document (student_number_id)');
    }
}
