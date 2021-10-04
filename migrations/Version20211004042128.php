<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211004042128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_73F2F77B12469DE2');
        $this->addSql('DROP INDEX IDX_73F2F77B126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__budget AS SELECT id, category_id, item_id, value, description, details FROM budget');
        $this->addSql('DROP TABLE budget');
        $this->addSql('CREATE TABLE budget (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, item_id INTEGER NOT NULL, value DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, details CLOB DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_73F2F77B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_73F2F77B126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO budget (id, category_id, item_id, value, description, details) SELECT id, category_id, item_id, value, description, details FROM __temp__budget');
        $this->addSql('DROP TABLE __temp__budget');
        $this->addSql('CREATE INDEX IDX_73F2F77B12469DE2 ON budget (category_id)');
        $this->addSql('CREATE INDEX IDX_73F2F77B126F525E ON budget (item_id)');
        $this->addSql('DROP INDEX IDX_1F1B251E12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item AS SELECT id, category_id, name, description, details FROM item');
        $this->addSql('DROP TABLE item');
        $this->addSql('CREATE TABLE item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, details CLOB DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_1F1B251E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO item (id, category_id, name, description, details) SELECT id, category_id, name, description, details FROM __temp__item');
        $this->addSql('DROP TABLE __temp__item');
        $this->addSql('CREATE INDEX IDX_1F1B251E12469DE2 ON item (category_id)');
        $this->addSql('DROP INDEX IDX_8F3F68C512469DE2');
        $this->addSql('DROP INDEX IDX_8F3F68C5126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__log AS SELECT id, category_id, item_id, operation, value, description, details, date FROM log');
        $this->addSql('DROP TABLE log');
        $this->addSql('CREATE TABLE log (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, item_id INTEGER NOT NULL, operation VARCHAR(255) NOT NULL COLLATE BINARY, value DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, details CLOB DEFAULT NULL COLLATE BINARY, date DATE NOT NULL, CONSTRAINT FK_8F3F68C512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8F3F68C5126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO log (id, category_id, item_id, operation, value, description, details, date) SELECT id, category_id, item_id, operation, value, description, details, date FROM __temp__log');
        $this->addSql('DROP TABLE __temp__log');
        $this->addSql('CREATE INDEX IDX_8F3F68C512469DE2 ON log (category_id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C5126F525E ON log (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_73F2F77B12469DE2');
        $this->addSql('DROP INDEX IDX_73F2F77B126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__budget AS SELECT id, category_id, item_id, value, description, details FROM budget');
        $this->addSql('DROP TABLE budget');
        $this->addSql('CREATE TABLE budget (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, item_id INTEGER NOT NULL, value DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, details CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO budget (id, category_id, item_id, value, description, details) SELECT id, category_id, item_id, value, description, details FROM __temp__budget');
        $this->addSql('DROP TABLE __temp__budget');
        $this->addSql('CREATE INDEX IDX_73F2F77B12469DE2 ON budget (category_id)');
        $this->addSql('CREATE INDEX IDX_73F2F77B126F525E ON budget (item_id)');
        $this->addSql('DROP INDEX IDX_1F1B251E12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item AS SELECT id, category_id, name, description, details FROM item');
        $this->addSql('DROP TABLE item');
        $this->addSql('CREATE TABLE item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, details CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO item (id, category_id, name, description, details) SELECT id, category_id, name, description, details FROM __temp__item');
        $this->addSql('DROP TABLE __temp__item');
        $this->addSql('CREATE INDEX IDX_1F1B251E12469DE2 ON item (category_id)');
        $this->addSql('DROP INDEX IDX_8F3F68C512469DE2');
        $this->addSql('DROP INDEX IDX_8F3F68C5126F525E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__log AS SELECT id, category_id, item_id, operation, value, description, details, date FROM log');
        $this->addSql('DROP TABLE log');
        $this->addSql('CREATE TABLE log (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, item_id INTEGER NOT NULL, operation VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, details CLOB DEFAULT NULL, date DATE NOT NULL)');
        $this->addSql('INSERT INTO log (id, category_id, item_id, operation, value, description, details, date) SELECT id, category_id, item_id, operation, value, description, details, date FROM __temp__log');
        $this->addSql('DROP TABLE __temp__log');
        $this->addSql('CREATE INDEX IDX_8F3F68C512469DE2 ON log (category_id)');
        $this->addSql('CREATE INDEX IDX_8F3F68C5126F525E ON log (item_id)');
    }
}
