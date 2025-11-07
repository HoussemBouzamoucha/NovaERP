<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251030171000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_project (users_id INT NOT NULL, project_id INT NOT NULL, PRIMARY KEY(users_id, project_id))');
        $this->addSql('CREATE INDEX IDX_DFB3A42467B3B43D ON users_project (users_id)');
        $this->addSql('CREATE INDEX IDX_DFB3A424166D1F9C ON users_project (project_id)');
        $this->addSql('ALTER TABLE users_project ADD CONSTRAINT FK_DFB3A42467B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_project ADD CONSTRAINT FK_DFB3A424166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inventory_users DROP CONSTRAINT fk_c81f0ee967b3b43d');
        $this->addSql('ALTER TABLE inventory_users DROP CONSTRAINT fk_c81f0ee99eea759');
        $this->addSql('ALTER TABLE invoice_users DROP CONSTRAINT fk_2959f55c2989f1fd');
        $this->addSql('ALTER TABLE invoice_users DROP CONSTRAINT fk_2959f55c67b3b43d');
        $this->addSql('ALTER TABLE project_users DROP CONSTRAINT fk_7d6ac77166d1f9c');
        $this->addSql('ALTER TABLE project_users DROP CONSTRAINT fk_7d6ac7767b3b43d');
        $this->addSql('DROP TABLE inventory_users');
        $this->addSql('DROP TABLE invoice_users');
        $this->addSql('DROP TABLE project_users');
        $this->addSql('ALTER TABLE inventory ADD users_id INT NOT NULL');
        $this->addSql('ALTER TABLE inventory ALTER supplier_id SET NOT NULL');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3667B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B12D4A3667B3B43D ON inventory (users_id)');
        $this->addSql('ALTER TABLE invoice ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER client_id SET NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER project_id SET NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER invoice_number TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE invoice RENAME COLUMN issued_at TO issue_date_at');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744F675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_90651744F675F31B ON invoice (author_id)');
        $this->addSql('ALTER TABLE leave_request DROP CONSTRAINT fk_7dc8f778a76ed395');
        $this->addSql('DROP INDEX idx_7dc8f778a76ed395');
        $this->addSql('ALTER TABLE leave_request ADD users_id INT NOT NULL');
        $this->addSql('ALTER TABLE leave_request ADD start_date_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE leave_request ADD end_date_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE leave_request DROP user_id');
        $this->addSql('ALTER TABLE leave_request DROP started_at');
        $this->addSql('ALTER TABLE leave_request DROP ended_at');
        $this->addSql('COMMENT ON COLUMN leave_request.start_date_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN leave_request.end_date_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE leave_request ADD CONSTRAINT FK_7DC8F77867B3B43D FOREIGN KEY (users_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7DC8F77867B3B43D ON leave_request (users_id)');
        $this->addSql('ALTER TABLE project ALTER client_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE inventory_users (inventory_id INT NOT NULL, users_id INT NOT NULL, PRIMARY KEY(inventory_id, users_id))');
        $this->addSql('CREATE INDEX idx_c81f0ee967b3b43d ON inventory_users (users_id)');
        $this->addSql('CREATE INDEX idx_c81f0ee99eea759 ON inventory_users (inventory_id)');
        $this->addSql('CREATE TABLE invoice_users (invoice_id INT NOT NULL, users_id INT NOT NULL, PRIMARY KEY(invoice_id, users_id))');
        $this->addSql('CREATE INDEX idx_2959f55c2989f1fd ON invoice_users (invoice_id)');
        $this->addSql('CREATE INDEX idx_2959f55c67b3b43d ON invoice_users (users_id)');
        $this->addSql('CREATE TABLE project_users (project_id INT NOT NULL, users_id INT NOT NULL, PRIMARY KEY(project_id, users_id))');
        $this->addSql('CREATE INDEX idx_7d6ac77166d1f9c ON project_users (project_id)');
        $this->addSql('CREATE INDEX idx_7d6ac7767b3b43d ON project_users (users_id)');
        $this->addSql('ALTER TABLE inventory_users ADD CONSTRAINT fk_c81f0ee967b3b43d FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inventory_users ADD CONSTRAINT fk_c81f0ee99eea759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_users ADD CONSTRAINT fk_2959f55c2989f1fd FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_users ADD CONSTRAINT fk_2959f55c67b3b43d FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_users ADD CONSTRAINT fk_7d6ac77166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_users ADD CONSTRAINT fk_7d6ac7767b3b43d FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_project DROP CONSTRAINT FK_DFB3A42467B3B43D');
        $this->addSql('ALTER TABLE users_project DROP CONSTRAINT FK_DFB3A424166D1F9C');
        $this->addSql('DROP TABLE users_project');
        $this->addSql('ALTER TABLE leave_request DROP CONSTRAINT FK_7DC8F77867B3B43D');
        $this->addSql('DROP INDEX IDX_7DC8F77867B3B43D');
        $this->addSql('ALTER TABLE leave_request ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE leave_request ADD started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE leave_request ADD ended_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE leave_request DROP users_id');
        $this->addSql('ALTER TABLE leave_request DROP start_date_at');
        $this->addSql('ALTER TABLE leave_request DROP end_date_at');
        $this->addSql('COMMENT ON COLUMN leave_request.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN leave_request.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE leave_request ADD CONSTRAINT fk_7dc8f778a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_7dc8f778a76ed395 ON leave_request (user_id)');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744F675F31B');
        $this->addSql('DROP INDEX IDX_90651744F675F31B');
        $this->addSql('ALTER TABLE invoice DROP author_id');
        $this->addSql('ALTER TABLE invoice ALTER client_id DROP NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER project_id DROP NOT NULL');
        $this->addSql('ALTER TABLE invoice ALTER invoice_number TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE invoice RENAME COLUMN issue_date_at TO issued_at');
        $this->addSql('ALTER TABLE inventory DROP CONSTRAINT FK_B12D4A3667B3B43D');
        $this->addSql('DROP INDEX IDX_B12D4A3667B3B43D');
        $this->addSql('ALTER TABLE inventory DROP users_id');
        $this->addSql('ALTER TABLE inventory ALTER supplier_id DROP NOT NULL');
        $this->addSql('ALTER TABLE project ALTER client_id DROP NOT NULL');
    }
}
