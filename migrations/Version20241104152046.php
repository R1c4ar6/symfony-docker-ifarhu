<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104152046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id SERIAL NOT NULL, student_id INT NOT NULL, student_number_id INT NOT NULL, pdf_path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8698A76CB944F1A ON document (student_id)');
        $this->addSql('CREATE INDEX IDX_D8698A7657CF1F90 ON document (student_number_id)');
        $this->addSql('CREATE TABLE student (id SERIAL NOT NULL, identification_number VARCHAR(32) NOT NULL, first_name VARCHAR(32) NOT NULL, last_name VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7657CF1F90 FOREIGN KEY (student_number_id) REFERENCES student (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76CB944F1A');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A7657CF1F90');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE student');
    }
}
