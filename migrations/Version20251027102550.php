<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027102550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inventory_users (inventory_id INT NOT NULL, users_id INT NOT NULL, PRIMARY KEY(inventory_id, users_id))');
        $this->addSql('CREATE INDEX IDX_C81F0EE99EEA759 ON inventory_users (inventory_id)');
        $this->addSql('CREATE INDEX IDX_C81F0EE967B3B43D ON inventory_users (users_id)');
        $this->addSql('CREATE TABLE invoice_users (invoice_id INT NOT NULL, users_id INT NOT NULL, PRIMARY KEY(invoice_id, users_id))');
        $this->addSql('CREATE INDEX IDX_2959F55C2989F1FD ON invoice_users (invoice_id)');
        $this->addSql('CREATE INDEX IDX_2959F55C67B3B43D ON invoice_users (users_id)');
        $this->addSql('ALTER TABLE inventory_users ADD CONSTRAINT FK_C81F0EE99EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inventory_users ADD CONSTRAINT FK_C81F0EE967B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_users ADD CONSTRAINT FK_2959F55C2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_users ADD CONSTRAINT FK_2959F55C67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE inventory_users DROP CONSTRAINT FK_C81F0EE99EEA759');
        $this->addSql('ALTER TABLE inventory_users DROP CONSTRAINT FK_C81F0EE967B3B43D');
        $this->addSql('ALTER TABLE invoice_users DROP CONSTRAINT FK_2959F55C2989F1FD');
        $this->addSql('ALTER TABLE invoice_users DROP CONSTRAINT FK_2959F55C67B3B43D');
        $this->addSql('DROP TABLE inventory_users');
        $this->addSql('DROP TABLE invoice_users');
    }
}
