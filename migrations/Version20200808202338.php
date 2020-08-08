<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200808202338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__cell AS SELECT id, ship_id, game_id, x_coordinate, y_coordinate, cellstate FROM cell');
        $this->addSql('DROP TABLE cell');
        $this->addSql('CREATE TABLE cell (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ship_id INTEGER DEFAULT NULL, game_id INTEGER NOT NULL, x_coordinate INTEGER NOT NULL, y_coordinate INTEGER NOT NULL, cellstate CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_CB8787E2C256317D FOREIGN KEY (ship_id) REFERENCES ship (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CB8787E2E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cell (id, ship_id, game_id, x_coordinate, y_coordinate, cellstate) SELECT id, ship_id, game_id, x_coordinate, y_coordinate, cellstate FROM __temp__cell');
        $this->addSql('DROP TABLE __temp__cell');
        $this->addSql('CREATE INDEX IDX_CB8787E2C256317D ON cell (ship_id)');
        $this->addSql('CREATE INDEX IDX_CB8787E2E48FD905 ON cell (game_id)');
        $this->addSql('DROP INDEX IDX_48DB750EE48FD905');
        $this->addSql('CREATE TEMPORARY TABLE __temp__placement AS SELECT id, game_id, xcoord, ycoord, orientation FROM placement');
        $this->addSql('DROP TABLE placement');
        $this->addSql('CREATE TABLE placement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER NOT NULL, xcoord INTEGER NOT NULL, ycoord INTEGER NOT NULL, orientation CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_48DB750EE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO placement (id, game_id, xcoord, ycoord, orientation) SELECT id, game_id, xcoord, ycoord, orientation FROM __temp__placement');
        $this->addSql('DROP TABLE __temp__placement');
        $this->addSql('CREATE INDEX IDX_48DB750EE48FD905 ON placement (game_id)');
        $this->addSql('DROP INDEX IDX_FA30EB24E48FD905');
        $this->addSql('DROP INDEX IDX_FA30EB24C54C8C93');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ship AS SELECT id, game_id, type_id, state FROM ship');
        $this->addSql('DROP TABLE ship');
        $this->addSql('CREATE TABLE ship (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER DEFAULT NULL, type_id INTEGER NOT NULL, state CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_FA30EB24E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FA30EB24C54C8C93 FOREIGN KEY (type_id) REFERENCES shiptype (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ship (id, game_id, type_id, state) SELECT id, game_id, type_id, state FROM __temp__ship');
        $this->addSql('DROP TABLE __temp__ship');
        $this->addSql('CREATE INDEX IDX_FA30EB24E48FD905 ON ship (game_id)');
        $this->addSql('CREATE INDEX IDX_FA30EB24C54C8C93 ON ship (type_id)');
        $this->addSql('DROP INDEX IDX_20201547E48FD905');
        $this->addSql('CREATE TEMPORARY TABLE __temp__turn AS SELECT id, game_id, xcoord, ycoord FROM turn');
        $this->addSql('DROP TABLE turn');
        $this->addSql('CREATE TABLE turn (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER NOT NULL, xcoord INTEGER NOT NULL, ycoord INTEGER NOT NULL, CONSTRAINT FK_20201547E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO turn (id, game_id, xcoord, ycoord) SELECT id, game_id, xcoord, ycoord FROM __temp__turn');
        $this->addSql('DROP TABLE __temp__turn');
        $this->addSql('CREATE INDEX IDX_20201547E48FD905 ON turn (game_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_CB8787E2C256317D');
        $this->addSql('DROP INDEX IDX_CB8787E2E48FD905');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cell AS SELECT id, ship_id, game_id, x_coordinate, y_coordinate, cellstate FROM cell');
        $this->addSql('DROP TABLE cell');
        $this->addSql('CREATE TABLE cell (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ship_id INTEGER DEFAULT NULL, game_id INTEGER NOT NULL, x_coordinate INTEGER NOT NULL, y_coordinate INTEGER NOT NULL, cellstate CLOB NOT NULL)');
        $this->addSql('INSERT INTO cell (id, ship_id, game_id, x_coordinate, y_coordinate, cellstate) SELECT id, ship_id, game_id, x_coordinate, y_coordinate, cellstate FROM __temp__cell');
        $this->addSql('DROP TABLE __temp__cell');
        $this->addSql('DROP INDEX IDX_48DB750EE48FD905');
        $this->addSql('CREATE TEMPORARY TABLE __temp__placement AS SELECT id, game_id, xcoord, ycoord, orientation FROM placement');
        $this->addSql('DROP TABLE placement');
        $this->addSql('CREATE TABLE placement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER NOT NULL, xcoord INTEGER NOT NULL, ycoord INTEGER NOT NULL, orientation CLOB NOT NULL)');
        $this->addSql('INSERT INTO placement (id, game_id, xcoord, ycoord, orientation) SELECT id, game_id, xcoord, ycoord, orientation FROM __temp__placement');
        $this->addSql('DROP TABLE __temp__placement');
        $this->addSql('CREATE INDEX IDX_48DB750EE48FD905 ON placement (game_id)');
        $this->addSql('DROP INDEX IDX_FA30EB24E48FD905');
        $this->addSql('DROP INDEX IDX_FA30EB24C54C8C93');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ship AS SELECT id, game_id, type_id, state FROM ship');
        $this->addSql('DROP TABLE ship');
        $this->addSql('CREATE TABLE ship (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER DEFAULT NULL, type_id INTEGER NOT NULL, state CLOB NOT NULL)');
        $this->addSql('INSERT INTO ship (id, game_id, type_id, state) SELECT id, game_id, type_id, state FROM __temp__ship');
        $this->addSql('DROP TABLE __temp__ship');
        $this->addSql('CREATE INDEX IDX_FA30EB24E48FD905 ON ship (game_id)');
        $this->addSql('CREATE INDEX IDX_FA30EB24C54C8C93 ON ship (type_id)');
        $this->addSql('DROP INDEX IDX_20201547E48FD905');
        $this->addSql('CREATE TEMPORARY TABLE __temp__turn AS SELECT id, game_id, xcoord, ycoord FROM turn');
        $this->addSql('DROP TABLE turn');
        $this->addSql('CREATE TABLE turn (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER NOT NULL, xcoord INTEGER NOT NULL, ycoord INTEGER NOT NULL)');
        $this->addSql('INSERT INTO turn (id, game_id, xcoord, ycoord) SELECT id, game_id, xcoord, ycoord FROM __temp__turn');
        $this->addSql('DROP TABLE __temp__turn');
        $this->addSql('CREATE INDEX IDX_20201547E48FD905 ON turn (game_id)');
    }
}
