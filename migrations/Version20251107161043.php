<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107161043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE department (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN department.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE task (id SERIAL NOT NULL, project_id INT DEFAULT NULL, assigned_to_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, status VARCHAR(255) NOT NULL, priority VARCHAR(255) NOT NULL, dead_line_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25166D1F9C ON task (project_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25F4BD7827 ON task (assigned_to_id)');
        $this->addSql('COMMENT ON COLUMN task.dead_line_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD department_id INT NOT NULL');
        $this->addSql('ALTER TABLE users DROP department');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1483A5E9AE80F5DF ON users (department_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9AE80F5DF');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25166D1F9C');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25F4BD7827');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP INDEX IDX_1483A5E9AE80F5DF');
        $this->addSql('ALTER TABLE users ADD department VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users DROP department_id');
    }
}
